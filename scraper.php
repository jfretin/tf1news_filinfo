<?
// This is a template for a PHP scraper on Morph (https://morph.io)
// including some code snippets below that you should find helpful

require 'scraperwiki.php';
require 'scraperwiki/simple_html_dom.php';
//
// // Read in a page
$html = scraperwiki::scrape("http://lci.tf1.fr/");
//
// // Find something on the page using css selectors
$dom = new simple_html_dom();
$dom->load($html);
$as = $dom->find("article.teaser figure.d-inline a img");

foreach ($as as $a) {
    $src = $a->src;
    if ($src != "") {
        $md5 = md5($src);
        if (scraperwiki::select("* from data where 'md5'='".$md5."'")) {
            echo $md5 . " already in DB!\n";
        } else {
            $img = base64_encode(file_get_contents($src));
            scraperwiki::save_sqlite(array('md5'), array('md5' => $md5, 'src' => $src, 'content' => $img));
            echo "saved " . $md5 . " in DB\n";
        }
    }
}
//
// // Write out to the sqlite database using scraperwiki library
// scraperwiki::save_sqlite(array('name'), array('name' => 'susan', 'occupation' => 'software developer'));
//
// // An arbitrary query against the database
// scraperwiki::select("* from data where 'name'='peter'")

// You don't have to do things with the ScraperWiki library. You can use whatever is installed
// on Morph for PHP (See https://github.com/openaustralia/morph-docker-php) and all that matters
// is that your final data is written to an Sqlite database called data.sqlite in the current working directory which
// has at least a table called data.
?>
