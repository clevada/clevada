<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use DB;
use File;
use Image;

class Block extends Model
{

    protected $fillable = [
        'type',
        'label',
        'content',
        'header',
        'extra',
        'module',
        'content_id',
        'template_id',
        'position',
        'hide',
        'created_by_user_id',
        'updated_by_user_id',
    ];

    protected $table = 'blocks';


    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function content()
    {
        return $this->hasOne(BlockContent::class, 'block_id');
    }

    /**
     * Regenerate content blocks
     *
     * @return null
     */
    public static function regenerate_content_blocks($module, $content_id)
    {
        if ($module == 'posts') $item = Post::find($content_id);
        if ($module == 'pages') $item = Page::find($content_id);

        if (!$item) return null;

        $blocks = Block::where('module', $module)
            ->where('content_id', $content_id)
            ->where('hide', 0)
            ->orderBy('position')
            ->select('id', 'type', 'extra')
            ->get()->toArray();

        if ($module == 'posts') Post::where('id', $content_id)->update(['blocks' => serialize($blocks)]);
        if ($module == 'pages') Page::where('id', $content_id)->update(['blocks' => serialize($blocks)]);

        return;
    }


    public static function update_block($id, $type, $request, $destination)
    {
        $inputs = $request->except('_token');

        if ($destination == 'blocks') {
            $table = 'blocks';
            $table_content = 'blocks_content';
        }       
        if ($destination == 'footer') {
            $table = 'template_footer_blocks';
            $table_content = 'template_footer_blocks_content';
        }


        if ($destination == 'blocks') $block_module = Block::where('id', $id)->value('module');

        // Extra content TEXT            
        if ($type == 'text') {            
            $content = array('title' => $request['title'], 'subtitle' => $request['subtitle'], 'content' => $request['content']);
            $content = serialize($content);

            DB::table($table)->where('id', $id)->update(['content' => $content]);
        }

        // Extra content EDITOR            
        if ($type == 'editor') {
            $content = $inputs['content'];

            // header data
            $header_array = array();
            $header_array = array('add_header' => $inputs['add_header'] ?? null, 'title' =>  $inputs["header_title"] ?? null, 'content' =>  $inputs["header_content"] ?? null);
            $header_content = serialize($header_array);

            DB::table($table)->where('id', $id)->update(['content' => $content, 'header' => $header_content]);
        }


        // Extra content POLL            
        if ($type == 'poll') {
            $block_extra = array();
            if ($inputs['use_custom_poll'] ?? null) $block_extra['poll_id'] = $inputs['poll_id'];

            DB::table($table)->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }


        // AD block         
        if ($type == 'ad') {
            $block_extra = array('bg_color' => null, 'ad_id' => $inputs['ad_id']);
            if ($inputs['use_custom_bg'] ?? null) $block_extra['bg_color'] = $inputs['bg_color'];
            DB::table($table)->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }


        // Extra content ACCORDION            
        if ($type == 'accordion') {
            $block_extra = array('bg_color' => null, 'title_size' => $inputs['title_size'], 'remove_border' => $inputs['remove_border'], 'title_color' => $inputs['title_color'], 'title_bg_color' => $inputs['title_bg_color'], 'font_color' => $inputs['font_color'] ?? null, 'collapse_first_item' => $inputs['collapse_first_item'] ?? null);
            if ($inputs['use_custom_font_color'] ?? null) $block_extra['use_custom_font_color'] = $inputs['use_custom_font_color'];
            if ($inputs['use_custom_bg'] ?? null) $block_extra['bg_color'] = $inputs['bg_color'];

            $group_array_key = array();

            if($_POST['title'] ?? null) {

                $counter_key = count(array_filter($_POST['title']));                

                for ($i = 0; $i < $counter_key; $i++) {
                    if (!(empty(array_filter($_POST['title']))))
                        $group_array_key[$i] = array('title' => $inputs["title"][$i], 'content' => $inputs["content"][$i]);
                }
                $content = serialize($group_array_key);
            }

            // header data
            $header_array = array();
            $header_array = array('add_header' => $inputs['add_header'] ?? null, 'title' =>  $inputs["header_title"] ?? null, 'content' =>  $inputs["header_content"] ?? null);
            $header_content = serialize($header_array);

            DB::table($table)->where('id', $id)->update(['extra' => serialize($block_extra), 'header' => $header_content, 'content' => $content ?? null]);
        }

        // Extra content ALERT            
        if ($type == 'alert') {
            $block_extra = array('type' => null);
            if ($inputs['alert_type'] ?? null) $block_extra['type'] = $inputs['alert_type'];

            $content_array = array('title' => $inputs["title"], 'content' => $inputs["content"]);
            $content = serialize($content_array);

            DB::table($table)->where('id', $id)->update(['extra' => serialize($block_extra), 'content' => $content]);
        }


        // Extra content VIDEO               
        if ($type == 'video') {
            $block_extra = array('full_width_responsive' => null);
            if ($inputs['full_width_responsive'] ?? null) $block_extra['full_width_responsive'] = $inputs['full_width_responsive'];

            $content = array('embed' => $request['embed'], 'caption' => $request['caption']);
            $content = serialize($content);

            // header data
            $header_array = array();
            $header_array = array('add_header' => $inputs['add_header'] ?? null, 'title' =>  $inputs["header_title"] ?? null, 'content' =>  $inputs["header_content"] ?? null);
            $header_content = serialize($header_array);


            DB::table($table)->where('id', $id)->update(['extra' => serialize($block_extra), 'header' => $header_content, 'content' => $content]);
        }

        // Extra content IMAGE      
        if ($type == 'image') {
            $block_extra = array('shadow' => null, 'rounded' => null);
            if ($inputs['shadow'] ?? null) $block_extra['shadow'] = $inputs['shadow'];
            if ($inputs['rounded'] ?? null) $block_extra['rounded'] = $inputs['rounded'];

            $image = null;
            if ($request->hasFile('image')) {
                $validator = Validator::make($request->all(), ['image' => 'file|image|max:5120']); // image mime, max 5 MB
                if (!$validator->fails())
                    $image = Upload::storeImage($request->file('image'), $oldImageCode = $request['existing_image'] ?? null, $data = array('module' => $block_module, 'item_id' => null, 'extra_item_id' => null));
            }
            $content = array('image' => $image->code ?? $request['existing_image'] ?? null, 'title' => $request['title'], 'caption' => $request['caption'], 'url' => $request['url']);
            $content = serialize($content);

            // header data
            $header_array = array();
            $header_array = array('add_header' => $inputs['add_header'] ?? null, 'title' =>  $inputs["header_title"] ?? null, 'content' =>  $inputs["header_content"] ?? null);
            $header_content = serialize($header_array);

            DB::table($table)->where(['id' => $id])->update(['extra' => serialize($block_extra), 'header' => $header_content, 'content' => $content]);
        }

        // Extra content GALLERY        
        if ($type == 'gallery') {
            $block_extra = array('shadow' => null, 'rounded' => null, 'cols' => null, 'masonry_layout' => null, 'masonry_cols' => null, 'masonry_gutter' => null);
            if ($inputs['shadow'] ?? null) $block_extra['shadow'] = $inputs['shadow'];
            if ($inputs['rounded'] ?? null) $block_extra['rounded'] = $inputs['rounded'];
            if ($inputs['cols'] ?? null) $block_extra['cols'] = $inputs['cols'] ?? 4;
            if ($inputs['masonry_layout'] ?? null) $block_extra['masonry_layout'] = $inputs['masonry_layout'];
            if ($inputs['masonry_cols'] ?? null) $block_extra['masonry_cols'] = $inputs['masonry_cols'] ?? 4;
            if ($inputs['masonry_gutter'] ?? null) $block_extra['masonry_gutter'] = $inputs['masonry_gutter'] ?? 0;


            $images_array_key = array();
            $counter_key_images = count(array_filter($_FILES['image']['name']));
            $counter_key_existing = count($inputs["existing_image"] ?? array());
            $counter_key = $counter_key_images + $counter_key_existing;

            $image = null;
            for ($i = 0; $i < $counter_key; $i++) {
                $image = null;

                // delete image (if checkbox is checked)
                if ($request->has('delete_image_' . $i)) {
                    $file_code_to_delete = $inputs['delete_image_file_code_' . $i];
                    delete_image($file_code_to_delete);
                } elseif ($request->hasFile('image')) {

                    if ($file = $request->file('image')[$i] ?? null) {
                        $validator = Validator::make($request->all(), ['image.' . $i => 'file|image|max:2560']); // image mime, max 2.5 MB    
                        if (!$validator->fails()) {
                            $img = Upload::storeImage($file, $oldImageCode = $inputs["existing_image"][$i] ?? null, $data = array('module' => $block_module, 'item_id' => null, 'extra_item_id' => null));
                            $image[$i] = $img->code;
                        }
                    }
                }

                if (!$request->has('delete_image_' . $i)) {
                    if (($image[$i] ?? null) || ($inputs["existing_image"][$i] ?? null))
                        $images_array_key[$i] = array('title' => $inputs["title"][$i], 'image' => $image[$i] ?? $inputs["existing_image"][$i] ?? null, 'caption' => $inputs["caption"][$i], 'position' => $inputs["position"][$i] ?? 1, 'url' => $inputs["url"][$i] ?? null);

                    // regenerate array and sort by position (asc)
                    if (count($images_array_key) > 0) {
                        $position = array_column($images_array_key, 'position');
                        array_multisort($position, SORT_ASC, $images_array_key);
                    }
                }
            }

            $content = serialize($images_array_key);

            // header data
            $header_array = array();
            $header_array = array('add_header' => $inputs['add_header'] ?? null, 'title' =>  $inputs["header_title"] ?? null, 'content' =>  $inputs["header_content"] ?? null);
            $header_content = serialize($header_array);

            DB::table($table)->where('id', $id)->update(['extra' => serialize($block_extra), 'header' => $header_content, 'content' => $content]);
        }



        // Extra content TESTIMONIAL        
        if ($type == 'testimonial') {
            $block_extra = array('use_star_rating' => null, 'use_image' => null, 'cols' => null);
            if ($inputs['use_star_rating'] ?? null) $block_extra['use_star_rating'] = $inputs['use_star_rating'];
            if ($inputs['use_image'] ?? null) $block_extra['use_image'] = $inputs['use_image'];
            if ($inputs['cols'] ?? null) $block_extra['cols'] = $inputs['cols'] ?? 3;

            $images_array_key = array();
            $counter_key = count(array_filter($_POST['name']));

            $image = null;

            for ($i = 0; $i < $counter_key; $i++) {
                $image = null;

                // delete image (if checkbox is checked)
                if ($request->has('delete_image_' . $i)) {
                    $file_code_to_delete = $inputs['delete_image_file_code' . '_' . $i];
                    delete_image($file_code_to_delete);
                    $inputs["existing_image"][$i] = null;
                }

                if ($request->hasFile('image')) {

                    if ($file = $request->file('image')[$i] ?? null) {
                        $validator = Validator::make($request->all(), ['image.' . $i => 'file|image|max:2560']); // image mime, max 2.5 MB    
                        if (!$validator->fails()) {
                            $img = Upload::storeImage($file, $oldImageCode = $inputs["existing_image"][$i] ?? null, $data = array('module' => $block_module, 'item_id' => null, 'extra_item_id' => null));
                            $image = $img->code;
                        }
                    }
                }

                $images_array_key[$i] = array('name' => $inputs["name"][$i], 'subtitle' => $inputs["subtitle"][$i], 'image' => $image ?? $inputs["existing_image"][$i] ?? null, 'rating' => $inputs["rating"][$i], 'position' => $inputs["position"][$i] ?? 1, 'testimonial' => $inputs["testimonial"][$i] ?? null);

                // regenerate array and sort by position (asc)
                if (count($images_array_key) > 1) {
                    $position = array_column($images_array_key, 'position');
                    array_multisort($position, SORT_ASC, $images_array_key);
                }
            }

            $content = serialize($images_array_key);

            // header data
            $header_array = array();
            $header_array = array('add_header' => $inputs['add_header'] ?? null, 'title' =>  $inputs["header_title"] ?? null, 'content' =>  $inputs["header_content"] ?? null);
            $header_content = serialize($header_array);

            DB::table($table)->where('id', $id)->update(['extra' => serialize($block_extra), 'header' => $header_content, 'content' => $content]);
        }


        // Extra content LINKS        
        if ($type == 'links') {
            $block_extra = array('bg_color' => null, 'new_tab' => null, 'display_style' =>  $inputs['display_style']);
            if ($inputs['use_custom_bg'] ?? null) $block_extra['bg_color'] = $inputs['bg_color'];
            if ($inputs['new_tab'] ?? null) $block_extra['new_tab'] = $inputs['new_tab'];
            DB::table($table)->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }

        // Extra content MAPS       
        if ($type == 'map') {
            $block_extra = array('height' => $inputs['height'] ?? 400, 'zoom' => $inputs['zoom'] ?? 16, 'address' => $inputs['address']);

            // Header data
            $header_array = array();
            $header_array = array('add_header' => $inputs['add_header'] ?? null, 'title' =>  $inputs["header_title"] ?? null, 'content' =>  $inputs["header_content"] ?? null);
            $header_content = serialize($header_array);

            DB::table($table)->where('id', $id)->update(['extra' => serialize($block_extra), 'header' => $header_content]);
        }

       

        // Extra content BLOCKQUOTE       
        if ($type == 'blockquote') {
            $block_extra = array('shadow' => null, 'use_avatar' => null, 'avatar' => $request['existing_avatar'] ?? null);

            if ($inputs['use_avatar'] ?? null) {
                if ($file = $request->file('avatar')) {
                    $validator = Validator::make($request->all(), ['avatar' => 'file|image|max:2048']); // image mime, max 2 MB    
                    if (!$validator->fails()) {
                        $img = Upload::storeImage($file, $oldImageCode = $inputs["existing_avatar"] ?? null, $data = array('module' => $block_module, 'item_id' => null, 'extra_item_id' => null));
                        $block_extra['avatar'] = $img->code;
                    }
                }
            }

            if ($inputs['shadow'] ?? null) $block_extra['shadow'] = $inputs['shadow'];
            if ($inputs['use_avatar'] ?? null) $block_extra['use_avatar'] = $inputs['use_avatar'];

            $content_array = array('source' => $inputs["source"], 'content' => $inputs["content"]);
            $content = serialize($content_array);

            DB::table($table)->where('id', $id)->update(['extra' => serialize($block_extra), 'content' => $content]);
        }


        
        // CUSTOM
        if ($type == 'custom') {
            $block_extra = array('bg_color' => null);
            if ($inputs['use_custom_bg'] ?? null) $block_extra['bg_color'] = $inputs['bg_color'];
            DB::table($table)->where('id', $id)->update(['extra' => serialize($block_extra)]);
        }

        

        // ***************************************************
        // Block CONTENT
        // ***************************************************      

        // UPDATE CONTENT
        $content = null;


        // CUSTOM
        if ($type == 'custom') {
            $content = $request['content'];
            DB::table($table)->where('id', $id)->update(['content' => $content]);
        }

        // LINKS
        if ($type == 'links') {
            $post_key_title = 'a_title';
            $post_key_url = 'a_url';
            $post_key_icon = 'a_icon';
            $links_array_key = 'links_array';
            $links_array_key = array();
            $counter_key = 'numb_items';
            $counter_key = count(array_filter($_POST[$post_key_url]));

            for ($i = 0; $i < $counter_key; $i++) {
                if (!(empty(array_filter($_POST[$post_key_title])) && empty(array_filter($_POST[$post_key_url]))))
                    $links_array_key[$i] = array('title' => $inputs["$post_key_title"][$i], 'url' => $inputs["$post_key_url"][$i], 'icon' => $inputs["$post_key_icon"][$i]);
            }
            $content = serialize($links_array_key);
            DB::table($table_content)->updateOrInsert(['block_id' => $id], ['content' => $content]);

            // header data
            $header_array = array();
            $header_array = array('add_header' => $inputs['add_header'] ?? null, 'title' =>  $inputs["header_title"] ?? null, 'content' =>  $inputs["header_content"] ?? null);
            $header_content = serialize($header_array);
            DB::table($table_content)->where(['block_id' => $id])->update(['header' => $header_content]);
        }
       
    }
}
