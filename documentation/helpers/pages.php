<?php 

function get_uri($path) {
  $regex = '/(http:\/\/)?'.str_replace(array('http://', '/'), array('', '\/'), config('base_url')).'(index.php)?\/?(?<uri>.*)/';
  preg_match($regex, $path, $matches);

  if(array_key_exists('uri', $matches))
    return $matches['uri'];
  return '';
}

function humanize_file_name($file) {
  $connectors_lower = array('and', 'or', 'a', 'an', 'the');
  $connectors_upper = array('And', 'Or', 'A', 'An', 'The');

  return str_replace($connectors_upper, $connectors_lower,
                     ucwords(str_replace('_', ' ',
                                         preg_replace('/\.[a-z]+$/', '',
                                                      preg_replace('/^\d*\./', '', $file)))));
}

function make_link($file_name, $base) {
  return '<a href="'.config('site_url').$base.$file_name.'">'.humanize_file_name($file_name).'</a>';
}

function generate_hierarchy($pages, $base = '') {
  $html = '<ul>';

  foreach($pages as $file_name => $page) {
    if(is_string($page)) {
      $html .= '<li>'.make_link($file_name, $base).'</li>';
    } else {
      $html .= '<li class="sub_menu_title">'.humanize_file_name($file_name).'</li>';
      $html .= '<li>'.generate_hierarchy($page, $base.$file_name.'/').'</li>';
    }
  }

  $html .= '</ul>';
  return $html;
}

function create_menu() {
  global $q_template;

  $pages = $q_template->get_page_hierachy();
  return '<div id="wrapper">
            <div id="menu">'.
              generate_hierarchy($pages).'
            </div>
            <div id="content">';
}

 ?>