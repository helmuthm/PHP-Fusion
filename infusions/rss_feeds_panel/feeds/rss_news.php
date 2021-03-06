<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: rss_weblinks.php
| Author: Robert Gaudyn (Wooya)
| Co-Author: Joakim Falk (Falk)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once dirname(__FILE__)."../../../../maincore.php";
header('Content-Type: application/rss+xml; charset='.$locale['charset'].'');

if (file_exists(INFUSIONS."rss_feeds_panel/locale/".LANGUAGE.".php")) {
    include INFUSIONS."rss_feeds_panel/locale/".LANGUAGE.".php";
} else {
    include INFUSIONS."rss_feeds_panel/locale/English.php";
}

if (db_exists(DB_NEWS)) {
    $result = dbquery("SELECT * FROM ".DB_NEWS." WHERE ".groupaccess('news_visibility').(multilang_table("NS") ? " AND news_language='".LANGUAGE."'" : "")."	ORDER BY news_datestamp DESC LIMIT 0,10");
    $rssimage = $settings['siteurl'].$settings['sitebanner'];

    echo "<?xml version=\"1.0\" encoding=\"".$locale['charset']."\"?>\n";
    echo "<rss version=\"2.0\" xmlns:content=\"http://purl.org/rss/1.0/modules/content/\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n<image>\n<url>$rssimage</url>\n</image>\n<channel>\n";

    if (dbrows($result) != 0) {
        echo "<title>".$settings['sitename'].' - '.$locale['rss_news'].(multilang_table("NS") ? $locale['rss_in'].LANGUAGE : "")."</title>\n";

        echo "<link>".$settings['siteurl']."</link>\n
	  <description>".$settings['description']."</description>\n";

        while ($row = dbarray($result)) {
            $rsid = intval($row['news_id']);
            $rtitle = $row['news_subject'];
            $description = stripslashes(nl2br($row['news_news']));
            $description = strip_tags($description, "<a><p><br /><br /><hr />");
            echo "<item>\n";
            echo "<title>".htmlspecialchars($rtitle)."</title>\n";
            echo "<link>".$settings['siteurl']."infusions/news/news.php?readmore=".$rsid."</link>\n";
            echo "<description><![CDATA[".html_entity_decode($description)."]]></description>\n";
            echo "</item>\n";
        }
    } else {
        echo "<title>".$settings['sitename'].' - '.$locale['rss_news']."</title>\n
	  <link>".$settings['siteurl']."</link>\n
	  <description>".$locale['rss_nodata']."</description>\n";
    }
    echo "</channel>\n</rss>";
}
