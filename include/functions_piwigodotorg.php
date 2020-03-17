<?php
/**
 * list of <page ids> => <language key for page title>. They are the default "porg=xxx" in URLs. We use "-" and not "_".
 */
function porg_get_pages()
{
  return array(
    'home' => 'Piwigo - Manage your photo collection',
    'features' => 'Features',
    'what-is-piwigo' => 'What is Piwigo?',
    'changelogs' => 'Changelogs',
    'contact' => 'Contact',
    'about-us' => 'About us',
    'extensions' => null,
    'get-involved' => 'Get Involved',
    'get-piwigo' => 'Get Piwigo',
    'get-started' => 'Get Started',
    'coding-activity' => 'Coding activity',
    'news' => 'News',
    'newsletters' => 'Newsletters',
    'press' => 'Press',
    'release' => null, // will be filled by include/release.inc.php
    'showcases' => null,
    'testimonials' => 'Testimonials',
    'mobile-apps-privacy-policy' => 'Privacy Policy for Mobile Apps',
    'demo' => 'Demo'
    );
}

/**
 * transforms a page id into a localized label. In French, "features" becomes "fonctionnalites" in the URL
 */
function porg_get_page_label($page)
{
  global $lang;

  if (isset($lang['porg_urls'][$page]))
  {
    return $lang['porg_urls'][$page];
  }

  return $page;
}

/**
 * returns the relative URL for a page id. The pattern can be changed with configuration param $conf['porg_url_rewrite']
 */
function porg_get_page_url($page)
{
  global $conf;

  if ('home' == $page)
  {
    return get_gallery_home_url();
  }

  $label = porg_get_page_label($page);

  if (isset($conf['porg_url_rewrite']) and $conf['porg_url_rewrite'])
  {
    return $label;
  }

  return 'index.php?porg='.$label;
}

/**
 * converts a page id into the file name. We use "_" instead of "-" in files (include/xxx.inc.php or template/xxx.tpl)
 */
function porg_page_to_file($porg_page)
{
  return str_replace('-', '_', $porg_page);
}

/**
 * list of all urls, used in header/footer (and in the middle of some pages).
 *
 * return associative array 'file id' => 'relative url to page', like 'what_is_piwigo' => 'piwigo-cest-quoi' (FR)
 */
function porg_get_page_urls()
{
  $porg_pages = array_keys(porg_get_pages());

  $porg_page_urls = array();
  foreach ($porg_pages as $porg_page)
  {
    $porg_page_urls[porg_page_to_file($porg_page)] = porg_get_page_url($porg_page);
  }

  return $porg_page_urls;
}

/**
 * list of all page labels
 *
 * erturn associative array 'page id' => 'page label'
 */
function porg_get_page_labels()
{
  $porg_pages = array_keys(porg_get_pages());

  $porg_page_labels = array();
  foreach ($porg_pages as $porg_page)
  {
    $porg_page_labels[$porg_page] = porg_get_page_label($porg_page);
  }

  return $porg_page_labels;
}

/**
 * returns the page id, based on a label. Returns false if nothing found.
 */
function porg_label_to_page($label)
{
  // specific for release-x.y.z : split to label+version
  $release_label = porg_get_page_label('release');
  if (preg_match('/^'.$release_label.'\-(\d+\.\d+\.\d+)$/', $label, $matches))
  {
    $label = $release_label;
    $_GET['version'] = $matches[1];
  }

  $newsletters_label = porg_get_page_label('newsletters');
  if (preg_match('/^'.$newsletters_label.'-(\d{8})$/', $label, $matches))
  {
    $label = $newsletters_label;
    $_GET['newsletter_id'] = $matches[1];
  }

  $porg_page_labels = porg_get_page_labels();
  $flip = array_flip($porg_page_labels);

  if (isset($flip[$label]))
  {
    return $flip[$label];
  }

  return false;
}

function porg_get_page_title($page)
{
  $porg_pages = porg_get_pages();

  $title = l10n($porg_pages[$page]);
  if ('home' != $page)
  {
    $title.= ' | Piwigo';
  }

  return $title;
}

/**
 * in case a release has a special release notes (like 2.9.0), we do not use the generic release.tpl template
 */
function porg_get_release_tpl($version)
{
  global $user;

  $candidate = PORG_PATH . 'language/'.$user['language'].'/template/release-' . $version . '.tpl';
  if (file_exists($candidate))
  {
    return $candidate;
  }

  $candidate = PORG_PATH . 'template/release-' . $version . '.tpl';
  if (file_exists($candidate))
  {
    return $candidate;
  }

  return PORG_PATH . 'template/release.tpl';
}

function get_showcases($exclude_ids=array())
{
  global $lang_info, $conf, $page;

  $cache_path = $conf['data_location'].'porg_showcases-'.$lang_info['code'].'.cache.php';
  if (!is_file($cache_path) or filemtime($cache_path) < strtotime('1 hour ago'))
  {
    $url = 'https://' . $page['porg_domain_prefix'] . 'piwigo.org/showcase/ws.php?format=php&method=pwg.tags.getImages&tag_name=Featured';

    $content = @file_get_contents($url);
    if ($content !== false)
    {
      $result = unserialize($content);
      file_put_contents($cache_path, serialize($result['result']['images']));
    }
  }
  $raw_images = unserialize(file_get_contents($cache_path));

  if (count($exclude_ids) > 0)
  {
    foreach ($raw_images as $idx => $showcase)
    {
      if (in_array($showcase['id'], $exclude_ids))
      {
        unset($raw_images[$idx]);
      }
    }
  }

  $max = 4;
  $rand_keys = array_rand($raw_images, $max);
  $final_images = array();
  foreach ($rand_keys as $showcase_id)
  {
     $final_images[] = $raw_images[$showcase_id];
  }

  return $final_images;
}

function porg_get_testimonials_sample()
{
  global $lang_info, $conf;

  include(PORG_PATH . '/data/testimonials.data.php');

  shuffle($testimonials);
  $testimonials_sample = array();
  foreach (array($lang_info['code'], 'en') as $lang_code)
  {
    foreach ($testimonials as $testimonial)
    {
      if ($testimonial['language'] == $lang_code)
      {
        $testimonial['is_cut'] = false;
        $max_length = 400;
        if (strlen($testimonial['content']) > $max_length)
        {
          $delimiter = '~#~';
          $lines = explode($delimiter, wordwrap(trim($testimonial['content']), $max_length, $delimiter));
          $testimonial['content'] = array_shift($lines);

          $testimonial['is_cut'] = true;
        }

        $testimonials_sample[] = $testimonial;

        if (count($testimonials_sample) == 4)
        {
           break;
        }
      }
    }

    if (count($testimonials_sample) == 4)
    {
      break;
    }
  }

  return $testimonials_sample;
}

function porg_get_latest_version()
{
  global $conf;

  $cache_path = $conf['data_location'].'porg_latest_version.cache.php';
  // echo "<pre>data  = ".filemtime(PORG_PATH.'/data/release.data.php')."\n";
  // echo "cache = ".filemtime($cache_path).'</pre>';
  if (!is_file($cache_path) or filemtime($cache_path) < filemtime(PORG_PATH.'/data/release.data.php'))
  {
    $latest_version = porg_get_latest_version_nocache();
    file_put_contents($cache_path, serialize($latest_version));
  }

  return unserialize(file_get_contents($cache_path));
}

function porg_get_latest_version_nocache()
{
  // echo '['.__FUNCTION__.'] called<br>';
  include(PORG_PATH . '/data/release.data.php');

  $latest_version_number = array_keys($porg_releases)[0];
  $latest_version = array_shift($porg_releases);
  $latest_version['version'] = $latest_version_number;
  // echo '<pre>'; print_r($latest_version); echo '</pre>';
  return $latest_version;
}

function porg_get_news($start, $count)
{
  global $lang_info, $conf, $page;

  $topics = null;

  $cache_path = $conf['data_location'].'porg_news-'.$lang_info['code'].'.cache.php';
  if (!is_file($cache_path) or filemtime($cache_path) < strtotime('15 minutes ago'))
  {
    $forum_url = 'http://'.$page['porg_domain_prefix'].'piwigo.org/forum';
    $url = $forum_url.'/news.php?format=json';

    $content = @file_get_contents($url);
    if ($content !== false)
    {
      $topics = json_decode($content, true);

      $doc = new DOMDocument();
      $i = 0;

      foreach ($topics as $idx => $topic)
      {
        // looking for the image in the message
        @$doc->loadHTML($topic['message']);

        $imgs = $doc->getElementsByTagName('img');

        foreach ($imgs as $img) {
          $topics[$idx]['img_src'] = str_replace('http://', 'https://', $img->getAttribute('src'));
          break;
        }

        $message = $topic['message'];
        $message = str_replace('<br />', ' ', $message);
        $message = strip_tags($message);

        $topics[$idx]['is_cut'] = false;
        $max_length = 150;
        if (strlen($message) > $max_length)
        {
          $delimiter = '~#~';
          $lines = explode($delimiter, wordwrap(trim($message), $max_length, $delimiter));
          $message = array_shift($lines);

          $topics[$idx]['is_cut'] = true;
        }

        $topics[$idx]['message'] = $message;
        $topics[$idx]['id'] = $topic['topic_id'];
        $topics[$idx]['posted'] = porg_date_format($topic['posted_on'], true);
        $topics[$idx]['url'] = $forum_url.'/viewtopic.php?id='.$topic['topic_id'];

        $topics[$idx]['state'] = 'right';
        if ($i++ % 2 == 0)
        {
          $topics[$idx]['state'] = 'left';
        }

      }

      file_put_contents($cache_path, serialize($topics));
    }
  }

  if (is_null($topics))
  {
    $topics = unserialize(file_get_contents($cache_path));
  }

  $topics_slice = array_slice($topics, $start, $count);

  end($topics_slice);
  $last_idx = key($topics_slice);
  $topics_slice[$last_idx]['last'] = true;

  return array(
    'total_count' => count($topics),
    'topics' => $topics_slice,
  );
}

function porg_get_newsletters($lang_code)
{
  include(PORG_PATH . "data/newsletters.data.php");

  if (isset($newsletters[$lang_code]))
  {
    $newsletters = $newsletters[$lang_code];

    foreach ($newsletters as $idx => $newsletter)
    {
      $newsletters[$idx]['id'] = $lang_code.'-'.$idx;
      $newsletters[$idx]['image'] = preg_replace('{http://([a-z]{2,3}\.)?piwigo.org/}', '//${1}piwigo.org/', $newsletters[$idx]['image']);
      $newsletters[$idx]['date_label'] = porg_date_format($idx);
      $newsletters[$idx]['url'] = porg_get_page_label('newsletters').'-'.str_replace('-', '', $idx);
    }

    return $newsletters;
  }

  return null;
}

function porg_display_newsletter($newsletter_id)
{
  global $user;

  $lang_code = explode('_', $user['language'])[0];

  $newsletter_file = PORG_PATH.'data/newsletters/'.$newsletter_id.'_'.$lang_code.'.html';
  if (file_exists($newsletter_file))
  {
    $content_lines = file($newsletter_file);

    $output_started = false;
    foreach ($content_lines as $line)
    {
      if ($output_started or preg_match('/^<!DOCTYPE/', $line))
      {
        echo str_replace('%tracker%', 'abcd', $line);
        $output_started = true;
      }
    }
  }

  exit();
}

function porg_date_format($datetime, $is_timestamp=false)
{
  global $lang_info;

  $timestamp = $datetime;
  if (!$is_timestamp)
  {
    // in case we have a date without time, we force time to be noon, to avoid
    // summer/winter time to impact the date when calling format_date
    if (preg_match('/^\d\d\d\d-\d\d-\d\d$/', $datetime))
    {
      $datetime.= ' 12:00:00';
    }
    $timestamp = strtotime($datetime);
  }

  if ('en' == $lang_info['code'])
  {
    return date("F jS, Y", $timestamp);
  }

  if ('de' == $lang_info['code'])
  {
    setlocale(LC_TIME, "de_DE.UTF-8");
    return strftime('%d. %B %Y', $timestamp);
  }

  return format_date($timestamp, array('day', 'month', 'year'));
}

function porg_get_nb_years()
{
  $d1 = new DateTime('2002-04-15');
  $d2 = new DateTime();
  $diff = $d2->diff($d1);
  return $diff->y;
}

function porg_set_pcom_urls()
{
  global $page, $template;

  $pcom_prefix = isset($page['porg_pcom_prefix']) ? $page['porg_pcom_prefix'] : '';

  $pcom_url = array(
    'pricing' => 'https://piwigo.com/pricing',
    'why' => 'https://piwigo.com/why',
    'blog' => 'https://piwigo.com/blog/',
    'clients' => 'https://piwigo.com/clients',
  );

  if ('fr.' == $pcom_prefix)
  {
    $pcom_url = array(
      'pricing' => 'https://'.$pcom_prefix.'piwigo.com/tarifs',
      'why' => 'https://'.$pcom_prefix.'piwigo.com/pourquoi-choisir-piwigo',
      'blog' => 'https://'.$pcom_prefix.'piwigo.com/blog/',
      'clients' => 'https://'.$pcom_prefix.'piwigo.com/clients',
    );
  }
  elseif ('de.' == $pcom_prefix)
  {
    $pcom_url['pricing'] = 'https://'.$pcom_prefix.'piwigo.com/preise';
    $pcom_url['why'] = 'https://'.$pcom_prefix.'piwigo.com/warum-piwigo-wählen';
    $pcom_url['clients'] = 'https://'.$pcom_prefix.'piwigo.com/kunden';
  }
  elseif ('es.' == $pcom_prefix)
  {
    $pcom_url['pricing'] = 'https://'.$pcom_prefix.'piwigo.com/precios';
    $pcom_url['why'] = 'https://'.$pcom_prefix.'piwigo.com/por-que';
    $pcom_url['clients'] = 'https://'.$pcom_prefix.'piwigo.com/clientes';
  }
  elseif ('it.' == $pcom_prefix)
  {
    $pcom_url['pricing'] = 'https://'.$pcom_prefix.'piwigo.com/plan';
    $pcom_url['why'] = 'https://'.$pcom_prefix.'piwigo.com/position';
    $pcom_url['clients'] = 'https://'.$pcom_prefix.'piwigo.com/examples';
  }

  $template->assign('PCOM_URL', $pcom_url);
}
?>
