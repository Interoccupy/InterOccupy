<?php
/**
 * Created by WpGetReady
 * First Version: Fernando Zorrilla de San martin
 * Date: 23/07/12
 * Time: 03:48 PM
 */

    /**
     *to be decide if it will be a generic class and googleAnalyzer will inherit from it.
     * Functionality to be included in Statcomm 1.7.50
      */
class linkAnalyzer {
    private $link;
    function __construct($link)
    {

        $this->link = $link;
        $this->parseLink(); //proceed to analyze the link

    }

    public function parseLink()
    {
        //Problem 1 of 100: parse_url does not return the array expected.
        //I need to strip part by part the url which is tedious.
        $parse= parse_url($this->link);

        //Seriously malformed will return false.
     /*
        if ($parse ==FALSE )
        {
            return;
        }
     */
        utilities::fl("PARSE:",$parse);
        //Parse the variables and return an array
            $variables=array();
            parse_str($parse['query'],$variables);
            utilities::fl("VARIABLES:",$variables );

        //Some variables come with a prefix amp;, clean it up
        $clean =array();
        foreach ($variables as $key =>$value)
        {
            $key = str_replace("amp;","",$key);
            $clean[$key] =$value;
        }
        utilities::fl("CLEANED:",$clean );
        return $parse;
    }

/*
 *
Importante links

https://developers.google.com/search-appliance/documentation/64/xml_reference?hl=es#request_overview
http://support.google.com/websearch/bin/answer.py?hl=en&answer=136861

(Pending revision)
http://www.our-picks.com/archives/2007/01/28/pro-guide-to-google-searches-part-i/
http://googlesystem.blogspot.com/2006/07/meaning-of-parameters-in-google-query.html

UPDATE: the url is important. The second example has /imgres=..... for searching the web for images.
This class is the beginning of a looong class analysis.



EXAMPLE 1
    |  sa = "t"
|  source = "web"
|  cd = "2"
|  ved = "0CBsQFjAB"
|  url = "http://wordpressready.com/2010/12/optimizing-statpress/"
|  rct = "j"
|  q = "alter table wp_statpress  modify column date int(8) NULL NULL,  modify column time time NULL NULL,  modify column ip varchar(15) NULL NULL,  modify column nation varchar(10) NULL NULL,  modify column os varchar(64) NULL NULL,  modify column browser varchar(64) NULL NULL,  modify column searchengine varchar(64) NULL NULL,  modify column spider varchar(64) NULL NULL,  modify column feed varchar(32) NULL NULL,  modify column user varchar(64) NULL NULL,  modify column timestamp timestamp NULL NULL, add index spider_nation (spider, nation), add index ip_date (ip, date), add index (agent(255)), add index (search(255)), add index (referrer(255)), add index feed_spider_os (feed, spider, os), add index (os), add index date_feed_spider (date, feed, spider), add index feed_spider_browser (feed, spider, browser), add index (browser);"
|  ei = "IxOrTYSlBY7-vQOU97yWCg"
|  usg = "AFQjCNGLseE9OCgRYn3lt8DHf6fvnmJl3Q"
|  sig2 = "sfnDlhLjDey-1ysilV9jzQ"

EXAMPLE 2

Array (
|  imgurl = "http://wordpressready.com/wp-content/uploads/babel-tower.jpg"
|  imgrefurl = "http://wordpressready.com/2010/12/wp-to-twitter-extension-for-qtranslate/"
|  usg = "__FETtFxMwT-7oj7eu_YBGWb2DJ2A="
|  h = "612"
|  w = "650"
|  sz = "137"
|  hl = "da"
|  start = "19"
|  zoom = "1"
|  tbnid = "8DA-JqDob_QACM:"
|  tbnh = "142"
|  tbnw = "146"
|  ei = "VHMYT7SuCYjPswab6pjPAg"
|  prev = "/search?q=babeltower&hl=da&sa=X&rls=com.microsoft:da:IE-Address&rlz=1I7ACAW_da&imgrefurl=http://www.arts-now.com/art-for-sale.php%3Fartwork_number%3D0190&imgurl=http://www.arts-now.com/uploads/artwork_miliukas_babel_tower1.jpg&w=300&h=400&sig=104325401386637326759&biw=1366&bih=682&tbs=simg:CAESEgm_1eeI2x4uMmSEy0rCZ2sn1eQ&tbm=isch"
|  itbs = "1"
|  iact = "hc"
|  vpx = "639"
|  vpy = "349"
|  dur = "3307"
|  hovh = "218"
|  hovw = "231"
|  tx = "119"
|  ty = "190"
|  sig = "104325401386637326759"
|  page = "1"
|  ved = "1t:429,r:18,s:0"
)


http://www.blueglass.com/blog/google-search-url-parameters-query-string-anatomy/?filter=0&num=100

Parameter analysis:

http://www.seomoz.org/ugc/the-ultimate-guide-to-the-google-search-parameters
q=query+goes+here  ->main search

%2Bterm
Appended to the q= parameter. %2B is the + sign encoded, Return results only  with the term used, with no pluralisations,
alternate tenses, or synonyms.

~term
Another one that's appended to the q= parameter. Returns results for the term used and synonyms.

define%3Aword
Yet another q= parameter add-on. Returns definitions for the word you put in.
Example: Shows as define:word

term * term two
And another q= parameter add-on. Returns results with listings that contain both words, with other words between them.
Example: n+n2, n-n2, n/n2, n*n2, n^n2 and n% of n2


So far:
http://www.blueglass.com/blog/google-search-url-parameters-query-string-anatomy/
The explanation will be as following
key|value
Explanation

pws=0
Turns off Google personalization of search results

num= (1… 100)
Controls how many search results are displayed per page

filter=0
Eliminates the “omitted results” or “similar results” filter, and allows all results to show in the SERP.

btnI=1
Google “I’m feeling lucky” search: takes you straight to the top Google search result.

gfns=1
Takes you to the first (organic) result for that term (works mostly for branded, navigational search queries) -> “Browse by Name” is a cross between Google Search and I’m Feeling Lucky

as_epq
Returns exact match results
Example: "query goes here"

as_oq  (OR operations)
Returns one of the search terms
Example: "query string" OR goes OR here

as_eq (- operation)
Returns results that do NOT include the term
Example: -don't -include -these -words

tbs=qdr: (d / w / m / y)
Returns results which are (one day / week / month / year) old

tbs=rltm:1
Returns “Most recent” results (real-time search)

as_filetype (filetype:)
Returns results that have the specified extension
Example: filetype:pdf

as_occt (=url / =title / =body / =links) (allinurl / allintitle / allintext / allinanchor)
Prompts Google to only search within URL / page title / backlinks

as_sitesearch (site:)
Restricts results to the specified domain or subdomain

as_rq (related:)
Returns pages relevant to the URL

as_lq (link:)
Returns pages linking to the URL

as_rights (Advanced search options)
Restricts search results to files/pages that have certain rights.
The options are:
(cc_publicdomain|cc_attribute|cc_sharealike|cc_noncommercial|cc_nonderived) - free to use or share
(cc_publicdomain|cc_attribute|cc_sharealike|cc_nonderived).-(cc_noncommercial) - free to use or share, including commercially
(cc_publicdomain|cc_attribute|cc_sharealike|cc_noncommercial).-(cc_nonderived) - free to use, share, or modify
(cc_publicdomain|cc_attribute|cc_sharealike).-(cc_noncommercial|cc_nonderived) - free to use, share, or modify commercially


safe=active (Advanced search options)
Turns on safe search

newwindow=1 (Google Search Settings)
Opens results in a new window


Google Suggest Tracking URL Parameters (???? What is this?)

aq=f (Google Suggest Tracking)
The user did not choose the query from the Google Suggest box, but the user has Google Suggest box enabled.

aq=n Google Suggest Tracking
(n stands for a number) The user clicked a corresponding Google Suggest result to navigate to the search query string

oq= Google Suggest Tracking
shows which were the words/letters at which point the user stopped typing in the search box and instead went to suggest box and choose the entry.

sa=X Google SERPs navigation behavior tracking
The user “searched within search” or clicked “More results from” below a result or used “related searches”

sa=2 Google SERPs navigation behavior tracking
The user search again using the field at the bottom of search results (after scrolling through all the initial search results)

sa=N Google SERPs navigation behavior tracking
The user clicked through the results paging links at the bottom of the SERPs

sourceid (Source of the query:)

“Navclient” (address bar)| “Navclient-ff” (Google Toolbar for Firefox) | “Firefox-a” (search box top right) | “Chromium (Google Chrome web browser)

swrnum Search Within Results
The number of results the initial query returned

as_q Search Within Results
When searching within results, the query is added as_q

oi (Universal search)
“Revisions_inline” = related searches
“News_group” = group of results from Google News
“Video_result_group” = group of results from Google Video
“video_result” = link on a thumbnail of a video
“Blogsearch_group” = set of results from Google Blog Search

resnum (Universal Search)
Number of a result within the group



Google Language and Localization URL Parameters

hl=
The interface language. List of interface languages
https://sites.google.com/site/tomihasa/google-language-codes

lr=
Restricts search results to the chosen language
https://developers.google.com/search-appliance/documentation/64/xml_reference?hl=es#request_subcollections

cr=country
Limits the search results to pages/sites from the certain location (use the country abbreviations in place for ..).
https://developers.google.com/adwords/api/docs/appendix/countrycodes?hl=es

gl=country
Displays the results you would find in a search conducted from that geographic location (can be inaccurate as you are still querying Google from another location).

gr=
Limits the search results to pages/sites from the certain region
https://developers.google.com/adwords/api/docs/appendix/provincecodes?hl=es

gcs=
Limits the search results to pages/sites from the certain (comes ONLY in combination with gr= parameter)
https://developers.google.com/adwords/api/docs/appendix/cities_us?hl=es
or http://www.redflymarketing.com/city-codes/

gpc=
Limits the search results to pages/sites from the area code (only works with gl=countryUS)

adtest=on
Turns off AdWords database connection, so your browsing won't show up as an impression, and will disable the URLs. Set to on to activate, and off to turn it off.

btnG=Search
Simulates a click on the normal Google results buttpm. Change to btnI to get the I'm Feeling Lucky button result.

ie=
Controls the input encoding settings. This defaults to UTF-8, and is worked out server-side, hence changing it doesn't do anything.

oe=
Controls the output encoding settings. Works in the same way as ie, so you can tinker away, but it won't do anything.

http://www.thatsseo.com/2010/10/google-search-localization-url-parameters-explained/

gm=
Limits the search results to pages/site from US designated metropolitan areas.
https://developers.google.com/adwords/api/docs/appendix/metrocodes?hl=es





         */


}
