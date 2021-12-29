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
use App\Models\User;
use App\Models\Core;
use App\Models\Upload;

if (!function_exists('get_config_value')) {
	function get_config_value($varname = null)
	{
		$data = DB::table('sys_config')->where('name', $varname)->value('value');
		return $data ?? null;
	}
}


if (!function_exists('logged_user')) {
	function logged_user()
	{

		//$logged_user = array('id' => null, 'name' => null, 'role' => null, 'role_id' => null, 'avatar' => null, 'email_verified_at' => null, 'count_basket_items' => null, 'count_unpaid_orders' => null);

		// auth
		if (Auth::check()) {
			$UserModel = new User();

			$logged_user_role = $UserModel->get_role_from_id(Auth::user()->role_id);

			DB::table('users')->where('id', Auth::user()->id)->update(['last_activity' => now()]);

			$logged_user = array('id' => Auth::user()->id, 'name' => Auth::user()->name, 'role' => $logged_user_role, 'role_id' => Auth::user()->role_id, 'avatar' => Auth::user()->avatar, 'email_verified_at' => Auth::user()->email_verified_at, 'count_basket_items' => $count_basket_items ?? null, 'count_unpaid_orders' => $count_unpaid_orders ?? null);

			return (object)$logged_user;
		} else return null;
	}
}


if (!function_exists('site_url')) {
	function site_url()
	{
		if (isset($_SERVER['HTTPS'])) $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
		else $protocol = 'http';

		return $protocol . "://" . $_SERVER['HTTP_HOST'];
	}
}


// check if a module is active
if (!function_exists('check_module')) {
	function check_module($module)
	{

		if (!$module) return false;

		$modules = DB::table('sys_modules')
			->where('status', 'active')
			->pluck('module')
			->toArray();

		if (in_array($module, $modules)) return true;
		else return false;
	}
}


// check if a module is active or inactive (not disabled)
if (!function_exists('check_admin_module')) {
	function check_admin_module($module)
	{

		if (!$module) return false;

		$modules = DB::table('sys_modules')
			->where('status', '!=', 'dev')
			->pluck('module')
			->toArray();

		if (in_array($module, $modules)) return true;
		else return false;
	}
}


if (!function_exists('module_meta')) {
	function module_meta($module, $lang_id)
	{

		$module_array = DB::table('sys_modules')->where('module', $module)->first();
		if (!$module_array) return array();

		$meta = DB::table('sys_modules_meta')
			->where('module_id', $module_array->id)
			->where('lang_id', $lang_id)
			->first();

		$data = array('meta_title' => $meta->meta_title ?? null, 'meta_description' => $meta->meta_description ?? null);

		return (object)$data;
	}
}


// get details for module (url, status, meta title, meta description...)
if (!function_exists('module')) {
	function module($identificator)
	{

		if (!$identificator) return (object)array('status' => null, 'url' => '#');

		if (!is_int($identificator))
			$module = DB::table('sys_modules')
			->leftJoin('sys_modules_meta', 'sys_modules.id', '=', 'sys_modules_meta.module_id')
			->select('sys_modules.*', 'sys_modules_meta.meta_title as meta_title', 'sys_modules_meta.meta_description as meta_description')
			->where('module', $identificator)->first();
			
		else
			$module = DB::table('sys_modules')
			->leftJoin('sys_modules_meta', 'sys_modules.id', '=', 'sys_modules_meta.module_id')
			->select('sys_modules.*', 'sys_modules_meta.meta_title as meta_title', 'sys_modules_meta.meta_description as meta_description')
			->where('id', $identificator)->first();

		if (!$module) return (object)array('status' => null, 'url' => '#');

		$module_url = route($module->route_web, ['lang' => (active_lang()->id == default_lang()->id) ? null : active_lang()->code]) ?? '#';
		$array = array('status' => $module->status, 'url' => $module_url, 'meta_title' => $module->meta_title, 'meta_description' => $module->meta_description);

		return (object)$array;
	}
}


/*
// get language from ID (if $term is numeric) or code (if $term is not numeric)
// return Array
*/
if (!function_exists('lang')) {
	function lang($term)
	{

		$lang = DB::table('sys_lang');

		if (is_numeric($term))
			$lang = $lang->where('id', $term);
		else
			$lang = $lang->where('code', $term);

		$lang = $lang->first();

		return $lang;
	}
}


// get active language
if (!function_exists('active_lang')) {
	function active_lang()
	{

		$lang = DB::table('sys_lang')
			->where('locale', App::getLocale() ?? null)
			->first();

		return $lang;
	}
}


// get default language
if (!function_exists('default_lang')) {
	function default_lang()
	{

		$lang = DB::table('sys_lang')
			->where('is_default', 1)
			->first();

		return $lang;
	}
}


// get active languages
if (!function_exists('languages')) {
	function languages()
	{
		$languages = DB::table('sys_lang')->where('status', 'active')->orderBy('is_default', 'desc')->orderBy('name', 'asc')->get();
		return (object)$languages;
	}
}


// get all languages (active and inactive)
if (!function_exists('sys_langs')) {
	function sys_langs()
	{
		$sys_langs = DB::table('sys_lang')->where('status', 'active')->orWhere('status', 'inactive')->orderBy('is_default', 'desc')->orderBy('name', 'asc')->get();
		return (object)$sys_langs;
	}
}


// get user extra details
if (!function_exists('user_extra')) {
	function user_extra($user_id, $extra_key)
	{

		// get key id
		$q = DB::table('users_extra_keys')->where('extra_key', $extra_key)->first();
		if ($q) $key_id = $q->id;
		else return null;

		// get value
		$value = DB::table('users_extra_values')->where('key_id', $key_id)->where('user_id', $user_id)->value('value');

		if (!isset($value) or $value == '') return null;
		else return $value;
	}
}


// get comments counter from IP
if (!function_exists('count_ip_comments')) {
	function count_ip_comments($ip, $table = 'null')
	{
		if (!$table) $table = 'posts_comments';

		$counter_all = DB::table($table)->where('ip', $ip)->count();
		$counter_pending = DB::table($table)->where('ip', $ip)->where('status', 'pending')->count();

		$data = array('all' => $counter_all, 'pending' => $counter_pending);

		return (object)$data;
	}
}


if (!function_exists('chekbox_permissions')) {
	function chekbox_permissions($permission_id, $user_id)
	{
		$exists = DB::table('users_permissions')->where('permission_id', $permission_id)->where('user_id', $user_id)->exists();

		if ($exists == 1) return true;
		else return false;
	}
}


if (!function_exists('check_access')) {
	function check_access($module, $permission = null)
	{
		$UserModel = new User();
		if(! Auth::check()) return false;
		$logged_user_role = $UserModel->get_role_from_id(Auth::user()->role_id);
		if ($logged_user_role == 'admin') return true;

		if (!$module) return false;

		// check if mdule is not disabled
		$check_module = DB::table('sys_modules')->where('module', $module)->where('status', '!=', 'disabled')->exists();
		if (!$check_module) return false;

		if ($permission) {
			$permission = DB::table('sys_permissions')->where('module', $module)->where('permission', $permission)->first();

			if (!$permission) return false;

			if (DB::table('users_permissions')->where('module', $module)->where('permission_id', $permission->id)->where('user_id', Auth::user()->id)->exists())
				return true;
			else
				return false;
		} else {
			if (DB::table('users_permissions')->where('module', $module)->where('user_id', Auth::user()->id)->exists())
				return true;
			else
				return false;
		}
	}
}

// check if a form ave reCAPTCHA enabled
if (!function_exists('check_form_have_recaptcha')) {
	function check_form_have_recaptcha($form_id)
	{
		if (!$form_id) return false;

		$form = DB::table('forms')->where('id', $form_id)->first();
		if (!$form) return false;

		if ($form->recaptcha == 1) return true;
		else return false;
	}
}

if (!function_exists('delete_image')) {
	function delete_image($file)
	{

		// delete main image
		$filename = getcwd() . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $file;

		if (file_exists($filename)) @unlink($filename);

		// delete thumb, if exists
		$pos = strrpos($file, DIRECTORY_SEPARATOR);
		if ($pos !== false) {
			$file = substr_replace($file, DIRECTORY_SEPARATOR . 'thumb_', $pos, 1);
		}

		// delete thumb square, if exists
		$pos = strrpos($file, DIRECTORY_SEPARATOR);
		if ($pos !== false) {
			$file = substr_replace($file, DIRECTORY_SEPARATOR . 'thumb_square_', $pos, 1);
		}

		$filename = getcwd() . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $file; // thumb
		if (file_exists($filename)) @unlink($filename);

		return;
	}
}


if (!function_exists('delete_file')) {
	function delete_file($file)
	{
		// delete file
		$filename = getcwd() . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $file;
		if (file_exists($filename)) @unlink($filename);
		return;
	}
}


if (!function_exists('estimated_reading_time')) {
	function estimated_reading_time($post_id)
	{

		$post = DB::table('posts')->where('id', $post_id)->first();
		if (!$post) return null;

		$words = 0;

		if (!$post->blocks) return 1;

		$blocks = unserialize($post->blocks);

		foreach ($blocks as $block) {
			$words_block = str_word_count(strip_tags($block->block_content_default_lang));
			$words = $words + $words_block;
		}

		$minutes = (int)($words / 120);
		$seconds = (int)($words % 120 / (120 / 60));

		if ($minutes < 1) $minutes = 1;

		return $minutes;
	}
}


// default currency
if (!function_exists('default_currency')) {
	function default_currency()
	{
		$currency = DB::table('sys_currencies')
			->where('is_default', 1)
			->where('active', 1)
			->first();

		return $currency;
	}
}


// default currency
if (!function_exists('currency')) {
	function currency($id)
	{
		$currency = DB::table('sys_currencies')
			->where('id', $id)
			->first();

		return $currency;
	}
}


// price format
if (!function_exists('price')) {
	function price($amount, $currency_id = null)
	{

		// active currency 
		if (!$currency_id)
			$currency = DB::table('sys_currencies')->where('is_default', 1)->first();
		else
			$currency = DB::table('sys_currencies')->where('id', $currency_id)->first();

		$value = number_format($amount, 2, $currency->d_separator ?? '.', $currency->t_separator ?? '');
		//$value = $value + 0;

		switch ($currency->style) {
			case 'value_code':
				$price = $value . ' ' . $currency->code;
				break;
			case 'code_value':
				$price = $currency->code . ' ' . $value;
				break;
			case 'value_symbol':
				$price = $value . ' ' . $currency->symbol;
				break;
			case 'symbol_value':
				$price = $currency->symbol . ' ' . $value;
				break;
			default:
				$price = $value . ' ' . $currency->code;
				break;
		}

		if ($currency->condensed == 1) $price = str_replace(' ', '', $price);

		return $price;
	}
}


// create breadcrumb for categories
if (!function_exists('breadcrumb_items')) {
	function breadcrumb_items($categ_id, $section = null)
	{

		if (!$section) $section = 'posts';

		if ($section == 'posts') $categ = DB::table('posts_categ')->where('posts_categ.id', $categ_id)->first();
		elseif ($section == 'forum') $categ = DB::table('forum_categ')->where('forum_categ.id', $categ_id)->first();	
		else return array();
	
		if (!$categ) return array();

		$items[] = array('id' => $categ->id, 'title' => $categ->title, 'slug' => $categ->slug, 'active' => $categ->active, 'icon' => $categ->icon, 'count_tree_items' => $categ->count_tree_items ?? null, 'count_tree_topics' => $categ->count_tree_topics ?? null, 'count_tree_posts' => $categ->count_tree_posts ?? null);

		$parent_id = $categ->parent_id;
		if ($parent_id) {
			$items =  array_merge($items, breadcrumb_items($parent_id, $section));
		}

		$items = json_decode(json_encode($items)); // array to object;
		return ($items);
	}
}


if (!function_exists('breadcrumb')) {
	function breadcrumb($categ_id, $section = null)
	{

		if (!$section) $section = 'posts';

		if (!$categ_id) return array();
		if (!is_array(breadcrumb_items($categ_id, $section))) return array();

		return array_reverse(breadcrumb_items($categ_id, $section));
	}
}


// create categ tree of the given category for forum 
if (!function_exists('forum_structure')) {
	function forum_categ_tree($categ_id = null)
	{

		$items = array();

		$q = DB::table('forum_categ')->where('parent_id', $categ_id)->where('active', 1)->orderBy('position', 'asc')->get();
		foreach ($q as $categ) {
			$latest_topic = array();
			$latest_post = array();
			$latest_activity = null;
			$categ_tree_ids = $categ->tree_ids ?? null;
			if ($categ_tree_ids) $categ_tree_ids_array = explode(',', $categ_tree_ids);

			$latest_topic_q = DB::table('forum_topics')
				->leftJoin('users', 'forum_topics.user_id', '=', 'users.id')
				->select('forum_topics.*', 'users.name as author_name', 'users.slug as author_slug', 'users.avatar as author_avatar')
				->whereIn('forum_topics.categ_id', $categ_tree_ids_array)
				->where('status', '!=', 'deleted')
				->orderBy('forum_topics.id', 'desc')
				->first();

			if ($latest_topic_q) {
				$latest_topic = array('id' => $latest_topic_q->id, 'slug' => $latest_topic_q->slug, 'title' => $latest_topic_q->title, 'created_at' => $latest_topic_q->created_at, 'author_user_id' => $latest_topic_q->user_id, 'author_name' => $latest_topic_q->author_name, 'author_slug' => $latest_topic_q->author_slug, 'author_avatar' => $latest_topic_q->author_avatar);
			}

			$latest_post_q = DB::table('forum_posts')
				->leftJoin('users', 'forum_posts.user_id', '=', 'users.id')
				->leftJoin('forum_topics', 'forum_posts.topic_id', '=', 'forum_topics.id')
				->select('forum_posts.*', 'users.name as author_name', 'users.slug as author_slug', 'users.avatar as author_avatar', 'forum_topics.title as topic_title', 'forum_topics.slug as topic_slug')
				->whereIn('forum_posts.categ_id', $categ_tree_ids_array)
				->orderBy('forum_posts.id', 'desc')
				->first();

			if ($latest_post_q) {
				$latest_post = array('id' => $latest_post_q->id, 'created_at' => $latest_post_q->created_at, 'author_user_id' => $latest_post_q->user_id, 'author_name' => $latest_post_q->author_name, 'author_slug' => $latest_post_q->author_slug, 'author_avatar' => $latest_post_q->author_avatar, 'topic_id' => $latest_post_q->topic_id, 'topic_title' => $latest_post_q->topic_title, 'topic_slug' => $latest_post_q->topic_slug);
			}

			// latest activity
			if ($latest_post_q && $latest_topic_q) {
				if ($latest_post_q->created_at >= $latest_topic_q->created_at) $latest_activity = 'post';
				else $latest_activity = 'topic';
			}
			if ($latest_post_q && !$latest_topic_q)  $latest_activity = 'post';
			if (!$latest_post_q && $latest_topic_q)  $latest_activity = 'topic';


			$items[] = array(
				'id' => $categ->id,
				'title' => $categ->title,
				'description' => $categ->description,
				'icon' => $categ->icon,
				'slug' => $categ->slug,
				'count_topics' => $categ->count_topics,
				'count_tree_topics' => $categ->count_tree_topics,
				'count_posts' => $categ->count_posts,
				'count_tree_posts' => $categ->count_tree_posts,
				'latest_topic' => $latest_topic,
				'latest_post' => $latest_post,
				'latest_activity' => $latest_activity ?? null,
				'tree_ids' => explode(',', $categ->tree_ids),
				'children' => forum_categ_tree($categ->id)
			);
		}

		return json_decode(json_encode($items)); // array to object;
	}
}


if (!function_exists('forum_topic_reports')) {
	function forum_topic_reports($topic_id)
	{
		return  DB::table('forum_reports')
			->leftJoin('users', 'forum_reports.from_user_id', '=', 'users.id')
			->select('forum_reports.*', 'users.name as from_name', 'users.email as from_email', 'users.avatar as from_avatar')
			->where('topic_id', $topic_id)
			->orderBy('id', 'desc')
			->paginate(20);
	}
}


if (!function_exists('forum_post_reports')) {
	function forum_post_reports($post_id)
	{
		return  DB::table('forum_reports')
			->leftJoin('users', 'forum_reports.from_user_id', '=', 'users.id')
			->select('forum_reports.*', 'users.name as from_name', 'users.email as from_email', 'users.avatar as from_avatar')
			->where('post_id', $post_id)
			->orderBy('id', 'desc')
			->paginate(20);
	}
}


// forum user statistics
if (!function_exists('forum_user_info')) {
	function forum_user_info($user_id)
	{

		$user = DB::table('users')->where('id', $user_id)->first();

		if (!$user) return null;

		$count_topics = DB::table('forum_topics')->where('user_id', $user_id)->where('status', 'active')->count();
		$count_posts = DB::table('forum_posts')->where('user_id', $user_id)->count();

		$items[] = array('count_topics' => $count_topics ?? 0, 'count_posts' => $count_posts ?? 0, 'slug' => $categ->slug);

		$parent_id = $categ->parent_id;
		if ($parent_id) {
			$items =  array_merge($items, nura_forum_categ_breadcrumb($parent_id));
		}

		return json_decode(json_encode($items)); // array to object;
	}
}


// check if user like forum cointent (post or topic)
if (!function_exists('forum_check_like')) {
	function forum_check_like($type, $content_id)
	{

		if (!Auth::check()) return;

		if ($type == 'post') {
			$check = DB::table('forum_likes')->where('user_id', Auth::user()->id)->where('post_id', $content_id)->first();
			if ($check) return true;
			else return false;
		}

		if ($type == 'topic') {
			$check = DB::table('forum_likes')->where('user_id', Auth::user()->id)->where('topic_id', $content_id)->first();
			if ($check) return true;
			else return false;
		}

		return;
	}
}


// check if user mark a topic as best answer
if (!function_exists('forum_check_best_answer')) {
	function forum_check_best_answer($post_id)
	{

		if (!Auth::check()) return;

		$check = DB::table('forum_best_answers')->where('user_id', Auth::user()->id)->where('post_id', $post_id)->first();
		if ($check) return true;
		else return false;
	}
}


if (!function_exists('forum_attachments')) {
	function forum_attachments($id, $type)
	{
		if ($type == 'topic') {
			$images = DB::table('forum_attachments')
				->where('topic_id', $id)
				->whereNull('post_id')
				->orderBy('id', 'desc')
				->paginate(24);
		}

		if ($type == 'post') {
			$images = DB::table('forum_attachments')
				->where('post_id', $id)
				->orderBy('id', 'desc')
				->paginate(24);
		}

		return $images ?? null;
	}
}


if (!function_exists('get_menu_link_label')) {
	function get_menu_link_label($link_id, $lang_id = null)
	{
		if (!$lang_id) $lang_id = default_lang()->id;

		$label = DB::table('sys_menu_langs')->where('link_id', $link_id)->where('lang_id', $lang_id)->value('label');

		return $label ?? null;
	}
}


if (!function_exists('get_footer_variable_value')) {
	function get_footer_variable_value($template, $variable)
	{
		$value = DB::table('sys_footers_config')->where('template', $template)->where('name', $variable)->value('value');
		return $value ?? null;
	}
}

if (!function_exists('get_template_value')) {
	function get_template_value($template_id, $variable)
	{
		$value = DB::table('sys_templates_config')->where('template_id', $template_id)->where('name', $variable)->value('value');
		return $value ?? null;
	}
}


// template variable from active template
if (!function_exists('template')) {
	function template($variable)
	{
		$templateID = DB::table('sys_templates')->where('is_default', 1)->value('id');
		$value = DB::table('sys_templates_config')->where('template_id', $templateID)->where('name', $variable)->value('value');
		return $value ?? null;
	}
}

if (!function_exists('template_font_sizes')) {
	function template_font_sizes()
	{
		$array[0] = (object)array('name' => '75%', 'value' => '0.75em');
		$array[1] = (object)array('name' => '80%', 'value' => '0.8em');
		$array[2] = (object)array('name' => '85%', 'value' => '0.85em');
		$array[3] = (object)array('name' => '90%', 'value' => '0.9em');
		$array[4] = (object)array('name' => '95%', 'value' => '0.95em');
		$array[5] = (object)array('name' => '100%', 'value' => '1em');
		$array[6] = (object)array('name' => '105%', 'value' => '1.05em');
		$array[7] = (object)array('name' => '110%', 'value' => '1.1em');
		$array[8] = (object)array('name' => '120%', 'value' => '1.2em');
		$array[9] = (object)array('name' => '130%', 'value' => '1.3em');
		$array[10] = (object)array('name' => '140%', 'value' => '1.4em');
		$array[11] = (object)array('name' => '150%', 'value' => '1.5em');
		$array[12] = (object)array('name' => '160%', 'value' => '1.6em');
		$array[13] = (object)array('name' => '175%', 'value' => '1.75em');
		$array[14] = (object)array('name' => '200%', 'value' => '2em');
		$array[15] = (object)array('name' => '250%', 'value' => '2.5em');
		$array[16] = (object)array('name' => '300%', 'value' => '3em');
		$array[17] = (object)array('name' => '350%', 'value' => '3.5em');
		$array[18] = (object)array('name' => '400%', 'value' => '4em');
		$array[19] = (object)array('name' => '450%', 'value' => '4.5em');
		$array[20] = (object)array('name' => '500%', 'value' => '5em');
		$array[21] = (object)array('name' => '600%', 'value' => '6em');

		return (object)$array;
	}
}


if (!function_exists('template_fonts')) {
	function template_fonts()
	{
		$array[0] = (object)array('value' =>  "'Alegreya', serif", 'name' => 'Alegreya');
		$array[1] = (object)array('value' =>  "'Alfa Slab One', cursive", 'name' => 'Alfa Slab One');
		$array[2] = (object)array('value' => "'Alegreya Sans', sans-serif", 'name' => 'Alegreya Sans');
		$array[3] = (object)array('value' => "'Anton', sans-serif", 'name' => 'Anton');
		$array[4] = (object)array('value' => "'Architects Daughter', cursive", 'name' => 'Architects Daughter');
		$array[5] = (object)array('value' => "'Archivo Black', sans-serif", 'name' => 'Archivo Black');
		$array[6] = (object)array('value' => "'Arima Madurai', cursive", 'name' => 'Arima Madurai');
		$array[7] = (object)array('value' => "'Arvo', serif", 'name' => 'Arvo');
		$array[8] = (object)array('value' => "'Bitter', serif", 'name' => 'Bitter');
		$array[9] = (object)array('value' => "'Courgette', cursive", 'name' => 'Courgette');
		$array[10] = (object)array('value' => "'Courier Prime', monospace", 'name' => 'Courier Prime');
		$array[11] = (object)array('value' => "'Crete Round', serif", 'name' => 'Crete Round');
		$array[12] = (object)array('value' => "'Dancing Script', cursive", 'name' => 'Dancing Script');
		$array[13] = (object)array('value' => "'Exo', sans-serif", 'name' => 'Exo');
		$array[14] = (object)array('value' => "'Fredoka One', cursive", 'name' => 'Fredoka One');
		$array[15] = (object)array('value' => "'Krub', sans-serif", 'name' => 'Krub');
		$array[16] = (object)array('value' => "'Lato', sans-serif", 'name' => 'Lato');
		$array[17] = (object)array('value' => "'Libre Baskerville', serif", 'name' => 'Libre Baskerville');
		$array[18] = (object)array('value' => "'Lora', serif", 'name' => 'Lora');
		$array[19] = (object)array('value' => "'Merriweather', serif", 'name' => 'Merriweather');
		$array[20] = (object)array('value' => "'Montserrat', sans-serif", 'name' => 'Montserrat');
		$array[21] = (object)array('value' => "'Nanum Gothic', sans-serif", 'name' => 'Nanum Gothic');
		$array[22] = (object)array('value' => "'Noto Sans', sans-serif", 'name' => 'Noto Sans');
		$array[23] = (object)array('value' => "'Nunito', sans-serif", 'name' => 'Nunito');
		$array[24] = (object)array('value' => "'Open Sans', sans-serif", 'name' => 'Open Sans');
		$array[25] = (object)array('value' => "'Oswald', sans-serif", 'name' => 'Oswald');
		$array[26] = (object)array('value' => "'Pacifico', cursive", 'name' => 'Pacifico');
		$array[27] = (object)array('value' => "'Playfair Display', serif", 'name' => 'Playfair Display');
		$array[28] = (object)array('value' => "'Poppins', sans-serif", 'name' => 'Poppins');
		$array[29] = (object)array('value' => "'Prata', serif", 'name' => 'Prata');
		$array[30] = (object)array('value' => "'Quicksand', sans-serif", 'name' => 'Quicksand');
		$array[31] = (object)array('value' => "'Raleway', sans-serif", 'name' => 'Raleway');
		$array[32] = (object)array('value' => "'Roboto', sans-serif", 'name' => 'Roboto');
		$array[33] = (object)array('value' => "'Roboto Mono', monospace", 'name' => 'Roboto Mono');
		$array[34] = (object)array('value' => "'Roboto Slab', sans-serif", 'name' => 'Roboto Slab');
		$array[35] = (object)array('value' => "'Source Code Pro', monospace", 'name' => 'Source Code Pro');
		$array[36] = (object)array('value' =>  "'Source Sans Pro', sans-serif", 'name' => 'Source Sans Pro');
		$array[37] = (object)array('value' =>  "'Source Serif Pro', sans-serif", 'name' => 'Source Serif Pro');
		$array[38] = (object)array('value' => "'Ubuntu', sans-serif", 'name' => 'Ubuntu');
		$array[39] = (object)array('value' => "'Urbanist', sans-serif", 'name' => 'Urbanist');
		$array[40] = (object)array('value' => "'Work Sans', sans-serif", 'name' => 'Work Sans');

		return (object)$array;
	}
}


if (!function_exists('get_block_types')) {
	function get_block_types($allow_section = null)
	{
		if (!$allow_section) $data = DB::table('blocks_types')->orderBy('position', 'asc')->get();
		else $data = DB::table('blocks_types')->orderBy('position', 'asc')->where($allow_section, 1)->get();
		return $data ?? null;
	}
}


if (!function_exists('form_field_lang')) {
	function form_field_lang($field_id, $lang_id)
	{
		$data = DB::table('forms_fields_lang')->where('field_id', $field_id)->where('lang_id', $lang_id)->first();
		return (object)$data ?? null;
	}
}

if (!function_exists('get_default_template_id')) {
	function get_default_template_id()
	{
		$default_template_id = DB::table('sys_templates')->where('is_default', 1)->value('id');
		return $default_template_id;
	}
}

if (!function_exists('get_block_css_style')) {
	function get_block_css_style($type)
	{
		$start = '/* start-'.$type.' */';
		$end = '/* end-'.$type.' */';

		$string = file_get_contents(public_path().'/templates/frontend/builder/assets/css/blocks.css');

		$string = ' ' . $string;
    	$ini = strpos($string, $start);
    	if ($ini == 0) return '';
    	$ini += strlen($start);
    	$len = strpos($string, $end, $ini) - $ini;
    	return substr($string, $ini, $len);

	}
	
}
