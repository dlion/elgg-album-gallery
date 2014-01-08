<?php
/**
 * Gallery
 *
 * A simple Gallery made to learn Elgg plugins enviroment
 */

elgg_register_event_handler('init', 'system', 'album_gallery_init');

/**
 * Init Gallery Plugin
 */
function album_gallery_init()
{
  // Add Library
  elgg_register_library('elgg:album_gallery', elgg_get_plugins_path().'elgg-album-gallery/lib/elgg-album-gallery.php');

  // Site navigation
  $item = new ElggMenuItem('elgg-album-gallery', 'Album Gallery', 'elgg-album-gallery/all');
  elgg_register_menu_item('site', $item);

  //Add custom CSS
  elgg_extend_view('css/elgg', 'elgg-album-gallery/css');

  /* Add some js files
    $gallery_js = elgg_get_simplecache_url('js', 'elgg-album-gallery/<name_file>');
    elgg_register_simplecache_view('js/elgg-album-gallery/<name_file>');
    elgg_register_js('elgg.mygallery',$gallery_js);
  */

	// routing of urls
  elgg_register_page_handler('elgg-album-gallery', 'album_gallery_page_handler');

  // register actions
  $action_path = elgg_get_plugins_path() . 'elgg-album-gallery/actions/elgg-album-gallery';
  //Image Upload
  elgg_register_action('elgg-album-gallery/uppa', "$action_path/uppa.php");
  //Delete Image
  elgg_register_action('elgg-album-gallery/delete', "$action_path/delete.php");
}

/**
 * Dispatches album-gallery pages.
 * URLs take the form of
 *  All images of user:       elgg-album-gallery/all
 *  Gallery Image:            elgg-album-gallery/view/<guid>
 *  New image:                elgg-album-gallery/add
 *  Delete image:             elgg-album-gallery/delete/<guid>
 */
function album_gallery_page_handler($page)
{
  elgg_load_library('elgg:album_gallery');

  if(!isset($page[0]))
    $page[0] = 'all';

  $page_type = $page[0];
  switch($page_type)
  {
    case 'view':
      $params = gallery_get_page_content_show($page[1]);
      break;
    case 'add':
      gatekeeper();
      $params = gallery_get_page_content_add();
      break;
    case 'delete':
      gatekeeper();
      $params = gallery_get_page_content_delete($page[1]);
      break;
    case 'all':
      $params = gallery_get_page_content_all();
      break;
    default:
      return false;
  }

  $body = elgg_view_layout('content', $params);

  echo elgg_view_page($params['title'], $body);
  return true;
}