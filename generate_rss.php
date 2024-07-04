<?php
// Define the RSS feed file
$rssFile = 'rss_feed.xml';

// Function to get the final redirected URL
function getFinalUrl($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true); // We want headers
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10); // Stop after 10 redirects
    curl_exec($ch);
    $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL); // The final URL after all redirects
    curl_close($ch);
    return $finalUrl;
}

// Get a random Wikipedia article URL
$randomArticleUrl = getFinalUrl("https://en.wikipedia.org/wiki/Special:Random");

// Load existing RSS feed or create a new one if it doesn't exist
if (file_exists($rssFile)) {
    $rss = new SimpleXMLElement(file_get_contents($rssFile));
} else {
    $rss = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rss version="2.0"><channel><title>Random Wikipedia Articles</title><link>https://prathmesh-feedspot.github.io/rss_feed.xml</link><description>A feed of random Wikipedia articles.</description><language>en-us</channel></rss>');
}

// Add new item to the RSS feed
$item = $rss->channel->addChild('item');
$item->addChild('title', 'Random Wikipedia Article');
$item->addChild('link', $randomArticleUrl);
$item->addChild('description', 'Check out this random Wikipedia article: ' . $randomArticleUrl);
$item->addChild('pubDate', date(DATE_RSS));

// Save the updated RSS feed
$rss->asXML($rssFile);

// Output success message
echo 'RSS feed updated successfully.';
?>
