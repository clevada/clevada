<?php

/**
 * Clevada: #1 Free Business Suite and Website Builder.
 *
 * Copyright (C) 2021  Chimilevschi Iosif Gabriel, https://clevada.com.
 *
 * LICENSE:
 * Clevada is licensed under the GNU General Public License v3.0
 * Permissions of this strong copyleft license are conditioned on making available complete source code 
 * of licensed works and modifications, which include larger works using a licensed work, under the same license. 
 * Copyright and license notices must be preserved. Contributors provide an express grant of patent rights.
 *    
 * @copyright   Copyright (c) 2021, Chimilevschi Iosif Gabriel, https://clevada.com.
 * @license     https://opensource.org/licenses/GPL-3.0  GPL-3.0 License.
 * @version     2.1.1
 * @author      Chimilevschi Iosif Gabriel <office@clevada.com>
 */

use Illuminate\Support\Facades\DB;
use App\Models\Core;

if (!function_exists('sys_config')) {
	function sys_config()
	{
		$results = DB::table('sys_config')->pluck('value', 'name')->toArray();
		return (object)$results;
	}
}


// Website general settings
if (!function_exists('site')) {
	function site()
	{

		$meta = DB::table('sys_lang')
			->where('id', active_lang()->id)
			->first();

		$homepage_url = route('homepage', ['lang' => (active_lang()->id == default_lang()->id) ? null : active_lang()->code]);

		$array = array(
			'url' => $homepage_url, // homepage URL (for active lang)
			'title' => $meta->site_short_title ?? null, // site short title (for active lang)
			'meta_title' => $meta->homepage_meta_title ?? null, // site meta title (for active lang)
			'meta_description' => $meta->homepage_meta_description ?? null, // site meta description (for active lang)
			'lang_name' => $meta->name ?? null, // active language name (Eg: English)
			'lang_code' => $meta->code ?? null, // active language code (Eg: en)
			'lang_locale' => $meta->locale ?? null, // active language locale (Eg: en_US)
		);

		return (object)$array;
	}
}

if (!function_exists('thumb')) {
	function thumb($identificator)
	{
		if (is_int($identificator)) // custom image added in media page
			$file = DB::table('media')->where('id', $identificator)->value('file');
		else 
			$file = $identificator;
		
		$pos = strrpos($file, DIRECTORY_SEPARATOR);

		if ($pos !== false) {
			$file = substr_replace($file, DIRECTORY_SEPARATOR . 'thumb_', $pos, 1);
		}		
		
		if(! $file) return asset("uploads" . DIRECTORY_SEPARATOR . 'default'.DIRECTORY_SEPARATOR.'no-image.png');		

		return asset("uploads" . DIRECTORY_SEPARATOR . $file);
	}
}

if (!function_exists('thumb_square')) {
	function thumb_square($identificator)
	{
		if (is_int($identificator)) // custom image added in media page
			$file = DB::table('media')->where('id', $identificator)->value('file');
		else 
			$file = $identificator;

		$pos = strrpos($file, DIRECTORY_SEPARATOR);

		if ($pos !== false) {
			$file = substr_replace($file, DIRECTORY_SEPARATOR . 'thumb_square_', $pos, 1);
		}

		if(! $file) return asset("uploads" . DIRECTORY_SEPARATOR . 'default'.DIRECTORY_SEPARATOR.'no-image.png');		

		return asset("uploads" . DIRECTORY_SEPARATOR . $file);
	}
}


if (!function_exists('image')) {
	function image($identificator)
	{
		if (is_int($identificator)) // custom image added in media page
			$file = DB::table('media')->where('id', $identificator)->value('file');
		else 
			$file = $identificator;			

		if(! $file) return asset("uploads" . DIRECTORY_SEPARATOR . 'default'.DIRECTORY_SEPARATOR.'no-image.png');		

		return asset("uploads" . DIRECTORY_SEPARATOR . $file);
	}
}


// format date
if (!function_exists('date_locale')) {
	function date_locale($date, $format = null)
	{

		$config = sys_config();

		$date_format = $config->date_format ?? '%B %e, %Y';


		if (!$format || $format == 'date') {
			return strftime($date_format, strtotime($date));
		}

		if ($format == 'datetime') {
			return strftime($date_format . ', %H:%M', strtotime($date));
		}

		if ($format == 'datetimefull') {
			return date_format(new DateTime($date), $date_format . ', H:i:s');
		}

		if ($format == 'daymonth') {
			return date_format(new DateTime($date), 'j M');
		}

		if ($format == 'time') {
			return date_format(new DateTime($date), 'H:i');
		}

		if ($format == 'timefull') {
			return date_format(new DateTime($date), 'H:i:s');
		}

		return;
	}
}



// Page details. (from pages_content table)
if (!function_exists('page')) {
	function page($id, $lang_id = null)
	{

		if (!$lang_id) $lang_id = default_lang()->id;

		$page = DB::table('pages')
			->leftJoin('pages_content', 'pages_content.page_id', '=', 'pages.id')
			->select('pages.*', 'pages.parent_id as parent_page_id', DB::raw("(SELECT slug FROM pages_content WHERE page_id = pages.parent_id AND lang_id = $lang_id) as parent_slug"))
			->where('active', 1)
			->where('pages.id', $id)
			->first();
		if (!$page) return null;

		$content = DB::table('pages_content')
			->where('pages_content.page_id', $id)
			->where('lang_id', $lang_id)
			->first();
		if (!$content) return null;

		//dd(default_lang()->id != $page->lang_id);

		if ($page->parent_id) // page is child of a parent page
			$page->url = route('child_page', ['lang' => (default_lang()->id != $content->lang_id) ? lang($content->lang_id)->code : null, 'slug' => $content->slug, 'parent_slug' => $page->parent_slug]);
		else
			$page->url = route('page', ['lang' => (default_lang()->id != $content->lang_id) ? lang($content->lang_id)->code : null, 'slug' => $content->slug]);		

		$page->child_pages = DB::table('pages')
			->leftJoin('pages_content', 'pages_content.page_id', '=', 'pages.id')
			->select(
				'pages.*',
				'pages.parent_id as parent_page_id',
				DB::raw("(SELECT slug FROM pages_content WHERE page_id = pages.parent_id AND lang_id = $content->lang_id) as parent_slug"),
				DB::raw("(SELECT title FROM pages_content WHERE page_id = pages.parent_id AND lang_id = $content->lang_id) as parent_title")
			)
			->where('pages.parent_id', $page->id)
			->where('pages_content.lang_id', $content->lang_id)
			->where('pages.active', 1)
			->orderBy('label', 'asc')
			->paginate(100);

		return $page;
	}
}



if (!function_exists('page_contents')) {
	function page_contents($id)
	{

		$page = DB::table('pages')->where('id', $id)->first();
		if (!$page) return null;

		$contents = DB::table('pages_content')
			->leftJoin('sys_lang', 'pages_content.lang_id', '=', 'sys_lang.id')
			->select('pages_content.*', 'sys_lang.name as lang_name', 'sys_lang.code as lang_code')
			->where('pages_content.page_id', $page->id)
			->orderBy('pages_content.lang_id', 'asc')
			->get();

		return $contents;
	}
}




// generate URL for community category, using category ID
// generate URL for community home, inf no category ID is passed
if (!function_exists('forum_url')) {
	function forum_url($categ_id = null)
	{
		if (!$categ_id)
			return route('forum');
		else {
			$categ = DB::table('forum_categ')
				->where('id', $categ_id)
				->first();
			if (!$categ) return;
			return route('forum.categ', ['slug' => $categ->slug]);
		}
	}
}


// latest forum topics
if (!function_exists('forum_topics')) {
	function forum_topics()
	{
		$topics = DB::table('forum_topics')
			->leftJoin('forum_categ', 'forum_topics.categ_id', '=', 'forum_categ.id')
			->leftJoin('users', 'forum_topics.user_id', '=', 'users.id')
			->select('forum_topics.*', 'forum_categ.title as categ_title', 'forum_categ.slug as categ_slug', 'users.name as author_name', 'users.slug as author_slug', 'users.avatar as author_avatar')
			->where('forum_topics.status', 'active')
			->orderBy('forum_topics.id', 'desc')
			->paginate(24);
		return $topics;
	}
}


// latest forum posts
if (!function_exists('forum_posts')) {
	function forum_posts()
	{
		$posts = DB::table('forum_posts')
			->leftJoin('forum_categ', 'forum_posts.categ_id', '=', 'forum_categ.id')
			->leftJoin('forum_topics', 'forum_posts.topic_id', '=', 'forum_topics.id')
			->leftJoin('users', 'forum_posts.user_id', '=', 'users.id')
			->select('forum_posts.*', 'forum_categ.title as categ_title', 'forum_categ.slug as categ_slug', 'forum_topics.id as topic_id', 'forum_topics.title as topic_title', 'forum_topics.slug as topic_slug', 'users.name as author_name', 'users.slug as author_slug', 'users.avatar as author_avatar')
			->where('forum_topics.status', 'active')
			->orderBy('forum_posts.id', 'desc')
			->paginate(24);

		return $posts;
	}
}


// get images gallery for a specific content and module
if (!function_exists('images')) {
	function images($id, $module)
	{
		$images = DB::table('website_media')
			->where('module', $module)
			->where('type', 'image')
			->where('content_id', $id)
			->orderBy('position', 'asc')
			->get();
		if (!$images) return array();
		return $images;
	}
}

// get videos for a specific content and module
if (!function_exists('videos')) {
	function videos($id, $module)
	{
		$images = DB::table('website_media')
			->where('module', $module)
			->where('type', 'videos')
			->where('content_id', $id)
			->orderBy('position', 'asc')
			->get();
		if (!$images) return array();
		return $images;
	}
}



// Latest posts (all categories or category ID)
if (!function_exists('posts')) {
	function posts($categ_id = null)
	{

		$modules = DB::table('sys_modules')->where('status', 'active')->pluck('module')->toArray();
		if (!in_array('posts', $modules)) return array();

		$posts = DB::table('posts')
			->leftJoin('users', 'posts.user_id', '=', 'users.id')
			->leftJoin('posts_categ', 'posts.categ_id', '=', 'posts_categ.id')
			->select('posts.*', 'posts_categ.title as categ_title', 'posts_categ.slug as categ_slug', 'users.name as author_name', 'users.email as author_email', 'users.avatar as author_avatar', 'users.slug as author_slug', DB::raw("(SELECT COUNT('id') FROM posts_comments WHERE posts_comments.post_id = posts.id AND posts_comments.status = 'active') as count_comments"))
			->where('status', 'active')
			->where('posts_categ.active', 1);

		if ($categ_id) {
			$categ = DB::table('posts_categ')->where('id', $categ_id)->where('posts_categ.active', 1)->first();
			$categ_tree_ids = $categ->tree_ids ?? null;
			if ($categ_tree_ids) $categ_tree_ids_array = explode(',', $categ_tree_ids);
			$posts = $posts->whereIn('posts.categ_id', $categ_tree_ids_array ?? array());
		}

		$posts = $posts->orderBy('posts.featured', 'desc')
			->orderBy('posts.id', 'desc')
			->paginate($config->posts_per_page ?? 24);

		return $posts;
	}
}



// generate URL for post category, using category ID
// generate URL for posts area, inf no category ID is passed
if (!function_exists('posts_url')) {
	function posts_url($categ_id = null)
	{
		if (!$categ_id)
			return route('posts', ['lang' => (active_lang()->id == default_lang()->id) ? null : active_lang()->code]);
		else {
			$categ = DB::table('posts_categ')
				->where('id', $categ_id)
				->first();
			if (!$categ) return;
			return route('posts.categ', ['lang' => (lang($categ->lang_id)->id == default_lang()->id) ? null : lang($categ->lang_id)->code, 'slug' => $categ->slug]);
		}
	}
}


// used in admin area
if (!function_exists('admin_posts_url')) {
	function admin_posts_url($categ_id)
	{
		$categ = DB::table('posts_categ')
			->leftJoin('sys_lang', 'posts_categ.lang_id', '=', 'sys_lang.id')
			->select('posts_categ.*', 'sys_lang.permalinks as permalinks')
			->where('posts_categ.id', $categ_id)
			->first();
		if (!$categ) return;

		$permalinks = unserialize($categ->permalinks);

		$lang = (lang($categ->lang_id)->id == default_lang()->id) ? null : '/' . lang($categ->lang_id)->code;
		$url = route('homepage') . $lang . '/' . $permalinks['posts'] . '/' . $categ->slug;

		return $url;
	}
}


// Return post details
if (!function_exists('post')) {
	function post($id)
	{
		$post = DB::table('posts')
			->leftJoin('posts_categ', 'posts.categ_id', '=', 'posts_categ.id')
			->leftJoin('sys_lang', 'posts.lang_id', '=', 'sys_lang.id')
			->select('posts.lang_id', 'posts.slug', 'posts_categ.slug as categ_slug', 'sys_lang.code as lang_code')
			->where('posts.id', $id)
			->where('posts.status', 'active')
			->first();
		if (!$post) return null;

		// check if language is active
		if (!DB::table('sys_lang')->where('id', $post->lang_id)->where('status', 'active')->exists()) return null;

		$post->url = route('post', ['lang' => (lang($post->lang_id)->id == default_lang()->id) ? null : lang($post->lang_id)->code, 'categ_slug' => $post->categ_slug, 'slug' => $post->slug]);

		return $post;
	}
}


// Return post details from admin (with specific lang)
if (!function_exists('admin_post')) {
	function admin_post($id)
	{

		$post = DB::table('posts')
			->leftJoin('posts_categ', 'posts.categ_id', '=', 'posts_categ.id')
			->leftJoin('sys_lang', 'posts.lang_id', '=', 'sys_lang.id')
			->select('posts.*', 'posts.slug', 'posts_categ.slug as categ_slug', 'sys_lang.code as lang_code', 'sys_lang.permalinks as permalinks')
			->where('posts.id', $id)
			->first();
		if (!$post) return null;

		// check if language is active
		if (!DB::table('sys_lang')->where('id', $post->lang_id)->where('status', 'active')->exists()) return null;

		$permalinks = unserialize($post->permalinks);

		$lang = (lang($post->lang_id)->id == default_lang()->id) ? null : '/' . lang($post->lang_id)->code;
		$post->url = route('homepage') . $lang . '/' . $permalinks['posts'] . '/' . $post->categ_slug . '/' . $post->slug;

		return $post;
	}
}


// generate URL for posts search results
if (!function_exists('posts_search_url')) {
	function posts_search_url()
	{
		return route('posts.search');
	}
}


// generate URL for posts tag
if (!function_exists('posts_tag_url')) {
	function posts_tag_url($tag)
	{
		if (!$tag) return null;
		return route('posts.tag');
	}
}


// generate URL for submitting a comment
if (!function_exists('posts_submit_comment_url')) {
	function posts_submit_comment_url($categ_slug, $post_slug, $lang = null)
	{
		if (!$categ_slug || !$post_slug) return null;
	
		return route('post.comment', ['categ_slug' => $categ_slug, 'slug' => $post_slug, 'lang' => $lang]);
	}
}


// generate URL for submitting a like
if (!function_exists('posts_submit_like_url')) {
	function posts_submit_like_url($categ_slug, $post_slug, $lang = null)
	{
		if (!$categ_slug || !$post_slug) return null;
		return route('post.like', ['categ_slug' => $categ_slug, 'slug' => $post_slug, 'lang' => $lang]);
	}
}


// generate URL for profile, from user ID
if (!function_exists('profile_url')) {
	function profile_url($user_id)
	{

		$user = DB::table('users')
			->where('id', $user_id)
			->where('active', 1)
			->where('is_deleted', 0)
			->first();
		if (!$user) return null;

		return route('profile', ['id' => $user->id, 'slug' => $user->slug]);
	}
}


// Get blocks for a specific content (post, page, sidebar, global (top / bottom sections), ....)
if (!function_exists('content_blocks')) {
	function content_blocks($module, $content_id, $template_id = null, $show_hidden = null)
	{		

		if(! $template_id) $template_id = Core::get_active_template_id();

		if($module == 'posts' && !$show_hidden) {		
			$blocks = DB::table('posts')->where('id', $content_id)->value('blocks');
			if(! $blocks) return array();
			$blocks = unserialize($blocks);			
		}

		elseif($module == 'pages' && !$show_hidden) {
			$blocks = DB::table('pages')->where('id', $content_id)->value('blocks');
			if(! $blocks) return array();
			$blocks = unserialize($blocks);		
		}

		elseif($module == 'docs' && !$show_hidden) {
			$blocks = DB::table('docs')->where('id', $content_id)->value('blocks');
			if(! $blocks) return array();
			$blocks = unserialize($blocks);		
		}

		else {
			$blocks = DB::table('blocks')
			->leftJoin('blocks_types', 'blocks.type_id', '=', 'blocks_types.id')
			->select(
				'blocks.*',
				'blocks_types.type as type',
				'blocks_types.label as type_label',
				'blocks.created_at as block_created_at',
				'blocks.updated_at as block_updated_at',
				'blocks.extra as block_extra',
				DB::raw("(SELECT content FROM blocks_content WHERE block_id = blocks.id AND lang_id = " . active_lang()->id . ") as block_content_default_lang")
			)
			->where('blocks.template_id', $template_id)
			->where('blocks.module', $module)
			->where('blocks.content_id', $content_id);

			if(! $show_hidden) $blocks = $blocks->where('blocks.hide', 0);			

			$blocks = $blocks->orderBy('position', 'asc')->get();
			if(! $blocks) return array();
		}

		return $blocks ?? array();
	}
}

// Get blocks for a specific template page
if (!function_exists('template_blocks')) {
	function template_blocks($module, $template_id = null, $show_hidden = null)
	{
		if(! $template_id) $template_id = Core::get_active_template_id();

		$blocks = DB::table('blocks')
			->leftJoin('blocks_types', 'blocks.type_id', '=', 'blocks_types.id')
			->select(
				'blocks.*',
				'blocks_types.type as type',
				'blocks_types.label as type_label',
				'blocks.created_at as block_created_at',
				'blocks.updated_at as block_updated_at',
				'blocks.extra as block_extra',
				DB::raw("(SELECT content FROM blocks_content WHERE block_id = blocks.id AND lang_id = " . active_lang()->id . ") as block_content_default_lang")
			)
			->where('blocks.template_id', $template_id)
			->where('blocks.module', $module);

		if(! $show_hidden) $blocks = $blocks->where('blocks.hide', 0);

		$blocks = $blocks->orderBy('position', 'asc')->get();

		return $blocks;
	}
}


// Get global blocks for a specific section 
if (!function_exists('global_blocks')) {
	function global_blocks($section_id)
	{

		if(! $section_id) return array();				

		$blocks = DB::table('blocks')
			->leftJoin('blocks_types', 'blocks.type_id', '=', 'blocks_types.id')
			->select(
				'blocks.*',
				'blocks_types.type as type',
				'blocks_types.label as type_label',
				'blocks.created_at as block_created_at',
				'blocks.updated_at as block_updated_at',
				'blocks.extra as block_extra',
				DB::raw("(SELECT content FROM blocks_content WHERE block_id = blocks.id AND lang_id = " . active_lang()->id . ") as block_content_default_lang")
			)
			->where('module', 'global')
			->where('content_id', $section_id)
			->where('blocks.hide', 0)
			->orderBy('position', 'asc')
			->get();

		return $blocks;
	}
}

// Get blocks for a specific footer column
if (!function_exists('footer_blocks')) {
	function footer_blocks($footer, $col, $template_id = null)
	{		
		if(! $template_id) $template_id = Core::get_active_template_id();
		if(! $template_id) return array();

		// get footer layout (number of columns)
		if($footer == 'primary') $layout = get_template_value($template_id, 'footer_columns') ?? 1;
        if($footer == 'secondary') $layout = get_template_value($template_id, 'footer2_columns') ?? 1;

		$blocks = DB::table('sys_footer_blocks')
			->leftJoin('blocks_types', 'sys_footer_blocks.type_id', '=', 'blocks_types.id')
			->select(
				'sys_footer_blocks.*',
				'blocks_types.type as type',
				'blocks_types.label as type_label',
				'sys_footer_blocks.created_at as block_created_at',
				'sys_footer_blocks.updated_at as block_updated_at',
				'sys_footer_blocks.extra as block_extra',
				DB::raw("(SELECT content FROM sys_footer_blocks_content WHERE block_id = sys_footer_blocks.id AND lang_id = " . active_lang()->id . ") as block_content_default_lang")
			)
			->where('template_id', $template_id)
			->where('footer', $footer)
			->where('layout', $layout)
			->where('col', $col)			
			->orderBy('position', 'asc')
			->get();			
		return $blocks;
	}
}


// show content block 
if (!function_exists('block')) {
	function block($id)
	{

		$active_lang_id = active_lang()->id ?? null;

		$data = array('block_extra' => null, 'content' => null, 'content_extra' => null);

		//$block_template = DB::table('blocks_template')->where('id', $id)->first();
		$block = DB::table('blocks')->where('id', $id)->where('hide', 0)->first();

		if (!$block) return (object)$data;

		$block_content = DB::table('blocks_content')
			->where('block_id', $id)
			->where('lang_id', $active_lang_id)
			->first();

		$data = array('content' => $block_content->content ?? null, 'header' => $block_content->header ?? null, 'block_extra' => (object)unserialize($block->extra));

		return (object)$data;
	}
}


// show footer block 
if (!function_exists('footer_block')) {
	function footer_block($id)
	{

		$active_lang_id = active_lang()->id ?? null;

		$data = array('block_extra' => null, 'content' => null, 'content_extra' => null);

		$block = DB::table('sys_footer_blocks')->where('id', $id)->first();

		if (!$block) return (object)$data;

		$block_content = DB::table('sys_footer_blocks_content')
			->where('block_id', $id)
			->where('lang_id', $active_lang_id)
			->first();

		$data = array('content' => $block_content->content ?? null, 'header' => $block_content->header ?? null, 'block_extra' => (object)unserialize($block->extra));

		return (object)$data;
	}
}


// get form fields (for active language)
if (!function_exists('form')) {
	function form($id)
	{
		$form = DB::table('forms')->where('id', $id)->where('active', 1)->first();		
		if(! $form) return null;

		$active_lang_id = active_lang()->id ?? null;		

		// get form fields
		$fields = DB::table('forms_fields')->where('form_id', $id)->where('active', 1)->orderBy('position', 'asc')->get();		

		$data = array();

		foreach($fields as $field) {
			
			$field_lang = DB::table('forms_fields_lang')->where('field_id', $field->id)->where('lang_id', $active_lang_id)->first();	

			$data[] = array('id' => $field->id, 'type' => $field->type, 'required' => $field->required, 'col_md' => $field->col_md, 'label' => $field_lang->label ?? null, 'info' => $field_lang->info ?? null, 'dropdowns' => $field_lang->dropdowns ?? null);
		}		

		return (object)$data ?? null;
	}
}

// generate flag image from language code
if (!function_exists('flag')) {
	function flag($code)
	{
		$lang = DB::table('sys_lang')->where('code', $code)->first();		
		if(! $lang) return null;
		
		if(file_exists(public_path('assets/img/flags/'.$lang->code.'.png'))) 
			return '<img alt="'.$lang->name.'" title="'.$lang->name.'" class="img-flag" src="'.asset("assets/img/flags/$lang->code.png").'">';
		else
			return null;
	}
}


// check if page have a form with reCAPTCHA renabled
if (!function_exists('check_page_recaptcha')) {
	function check_page_recaptcha($module, $content_id = null)
	{
		
		if (! ((Core::config()->google_recaptcha_enabled ?? null) && (Core::config()->google_recaptcha_site_key ?? null) && (Core::config()->google_recaptcha_secret_key ?? null)))
		return false;

		$block_type_id_form = DB::table('blocks_types')->where('type', 'form')->value('id');		

		// get blocks with form 
		$blocks = DB::table('blocks')->where('module', $module)->where('content_id', $content_id)->where('type_id', $block_type_id_form)->get();
		foreach ($blocks as $block) {			
			$block_extra = DB::table('blocks')->where('id', $block->id)->value('extra');
			$extra_array = unserialize($block_extra);
			$form_id = $extra_array['form_id'];

			if($form_id) {
				$recaptcha = DB::table('forms')->where('id', $form_id)->value('recaptcha');
				if($recaptcha == 1) return true;
			}			
		}
	
		// check posts comments
		if($module == 'posts') {
			if (($config->posts_comments_antispam_enabled ?? null)) return true;
		}

		return false;
		
	}
}

