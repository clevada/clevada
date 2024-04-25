<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Auth;
use App\Models\Page;
use App\Models\Tools;
use App\Models\TemplateMenu;
use App\Models\Block;

class PageController extends Controller
{

    /**
     * Display all resources
     */
    public function index(Request $request)
    {
        // check if user can view items
        if ($request->user()->cannot('view', Page::class)) return redirect(route('admin'))->withErrors('Forbidden');

        $search_terms = $request->search_terms;

        $pages = Page::with('author', 'parent')
            ->whereNull('deleted_at')
            ->orderByDesc('pages.active')
            ->orderByDesc('pages.id');

        if ($search_terms) $pages = $pages->where('title', 'like', "%$search_terms%");

        // check if user can view own items only
        if (!$request->user()->can('viewAny', Page::class)) $pages = $pages->where('user_id', $request->user()->id);

        $pages = $pages->paginate(25);

        $count_draft = Page::where('active', 0)->count();

        return view('admin.index', [
            'view_file' => 'pages.index',
            'active_menu' => 'pages',
            'search_terms' => $search_terms,
            'pages' => $pages,
            'count_draft' => $count_draft,
        ]);
    }


    /**
     * Show form to add new resource
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create', Page::class)) return redirect(route('admin.pages.index'))->withErrors('Forbidden');

        return view('admin.index', [
            'view_file' => 'pages.create',
            'active_menu' => 'pages',
            'root_pages' => Page::whereNull('parent_id')->orderByDesc('active')->get(),
        ]);
    }


    /**
     * Create new page
     */
    public function store(Request $request)
    {
        if ($request->user()->cannot('create', Page::class)) return redirect(route('admin.pages.index'))->withErrors('Forbidden');

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        if ($request->slug) $slug = Str::slug($request->slug, '-');
        else $slug = Str::slug($request->title, '-');

        $page = Page::create([
            'parent_id' => $request->parent_id,
            'user_id' => Auth::user()->id,
            'title' => $request->title,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'slug' => $slug,
            'active' => 0,
        ]);

        if (Page::where('slug', $slug)->where('id', '!=', $page->id)->exists()) $slug =  $slug . "-" . $page->id;
        Page::where('id', $page->id)->update(['slug' => $slug]);

        Tools::generateSitemap();

        return redirect(route('admin.pages.content', ['id' => $page->id]))->with('success', 'created');
    }


    /**
     * Show form to edit resource     
     */
    public function show(Request $request)
    {
        $page = Page::where('id', $request->id);

        // check if user can view own posts only
        if (!$request->user()->can('viewAny', Page::class)) $page = $page->where('user_id', $request->user()->id);

        $page = $page->first();

        if (!$page) return redirect(route('admin.pages.index'));
        if ($page->deleted_at && Auth::user()->role != 'admin') return redirect(route('admin.pages.index'));

        if ($request->user()->cannot('view', $page)) return redirect(route('admin.pages.index'))->withErrors('Forbidden');

        return view('admin.index', [
            'view_file' => 'pages.update',
            'active_menu' => 'pages',
            'nav_menu' => 'details',
            'page' => $page,
            'root_pages' => Page::whereNull('parent_id')->orderByDesc('active')->get(),
        ]);
    }


    /**
     * Update the specified resource     
     */
    public function update(Request $request)
    {

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $page = Page::find($request->id);
        if (!$page) return redirect(route('admin.pages.index'));

        if ($request->user()->cannot('update', $page)) return redirect(route('admin.pages.index'))->withErrors('Forbidden');

        if ($request->slug) $slug = Str::slug($request->slug, '-');
        else $slug = Str::slug($request->title, '-');
        if (Page::where('slug', $slug)->where('id', '!=', $request->id)->exists())  $slug =  $slug . "-" . $request->id;

        Page::where('id', $request->id)->update([
            'parent_id' => $request->parent_id ?? null,
            'active' => $request->has('active') ? 1 : 0,
            'title' => $request->title,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'slug' => $slug,
        ]);

        // regenerate menu links for each language and store in cache config
        TemplateMenu::generate_menu_links();
        Tools::generateSitemap();

        if ($request->redirect == 'return')
            return redirect(route('admin.pages.show', ['id' => $request->id]))->with('success', 'updated');
        elseif ($request->redirect == 'content')
            return redirect(route('admin.pages.content', ['id' => $request->id]))->with('success', 'updated');
        else
            return redirect(route('admin.pages.index'))->with('success', 'updated');
    }



    /**
     * Remove the specified resource
     */
    public function destroy(Request $request)
    {

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $page = Page::find($request->id);
        if (!$page) return redirect(route('admin.pages.index'));

        if ($request->user()->cannot('delete', $page)) return redirect(route('admin.pages.index'))->withErrors('Forbidden');

        Page::where('parent_id', $request->id)->update(['deleted_at' => now()]);
        Page::where('id', $request->id)->update(['deleted_at' => now()]);

        /*
        // delete content blocks
        $blocks = Block::where('module', 'pages')->where('content_id', $request->id)->get();
        foreach ($blocks as $block) {
            Block::where('id', $block->id)->delete();
            BlockContent::where('block_id', $block->id)->delete();
        }

        Page::where('parent_id', $request->id)->update(['parent_id' => null]);
        TemplateMenu::where('type', 'page')->where('value', $request->id)->delete();
        Page::where('id', $request->id)->delete();
        PageContent::where('page_id', $request->id)->delete();

        // regenerate menu links for each language and store in cache config
        TemplateMenu::generate_menu_links();
        */

        Tools::generateSitemap();

        return redirect(route('admin.pages.index'))->with('success', 'deleted');
    }



    /**
     * Show form to edit content     
     */
    public function content(Request $request)
    {
        // Retrieve page content
        $page = Page::find($request->id);
        if (!$page) return redirect(route('admin.pages.index'));

        if ($request->user()->cannot('view', $page)) return redirect(route('admin.pages.index'))->withErrors('Forbidden');

        return view('admin.index', [
            'view_file' => 'pages.content',
            'active_menu' => 'pages',
            'nav_menu' => 'content',
            'page' => $page,
            'module' => 'pages',
        ]);
    }



    /**
     * Update page content   
     */

    public function content_update(Request $request)
    {

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $page = Page::find($request->id);
        if (!$page) return redirect(route('admin.pages.index'));

        if ($request->user()->cannot('update', $page)) return redirect(route('admin.pages.index'))->withErrors('Forbidden');

        if (!$request->type) return redirect(route('admin.pages.content', ['id' => $request->id]));

        $last_pos = Block::where('module', 'pages')->where('content_id', $request->id)->orderByDesc('position')->value('position');
        $position = ($last_pos ?? 0) + 1;

        $block = Block::create([
            'type' => $request->type,
            'module' => 'pages',
            'label' => $request->label ?? null,
            'content_id' => $page->id,
            'position' => $position,
            'created_by_user_id' =>  Auth::user()->id
        ]);

        Block::regenerate_content_blocks('pages', $request->id);

        return redirect(route('admin.blocks.show', ['id' => $block->id]));
    }



    /**
     * Remove the specified block content
     */
    public function content_destroy(Request $request)
    {

        // disable action in demo mode:
        if (config('app.demo_mode')) return redirect(route('admin'))->with('error', 'demo');

        $page = Page::find($request->id);
        if (!$page) return redirect(route('admin.pages.index'));

        if ($request->user()->cannot('update', $page)) return redirect(route('admin.pages.index'))->withErrors('Forbidden');

        Block::where('id', $request->block_id)->delete();

        // regenerate content blocks and add blocks in module table (for database performance)
        Block::regenerate_content_blocks('pages', $request->id);

        return redirect(route('admin.pages.content', ['id' => $request->id]))->with('success', 'deleted');
    }


    /**
     * Ajax sortable
     */
    public function sortable(Request $request)
    {
        if ($request->user()->cannot('update', Page::class)) return;

        $i = 0;
        $records = $request->all();

        foreach ($records['item'] as $key => $value) {

            Block::where('module', 'pages')
                ->where('content_id', $request->id)
                ->where('id', $value)
                ->update([
                    'position' => $i,
                ]);

            $i++;
        }

        Block::regenerate_content_blocks('pages', $request->id);
    }


    public function ajaxPublishSwitch(Request $request)
    {
        $page = Page::find($request->id);
        if (!$page) return redirect(route('admin.pages.index'));

        if ($request->user()->cannot('update', $page)) return;

        Page::where('id', $request->id)->update(['active' => 1]);
        return;
    }
}
