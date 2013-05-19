<?php
$imgdir=  plugin_dir_url(__FILE__) . "/images/";
?>
<br>
<table style="text-align: left; width: 100%;" cellpadding="2" cellspacing="2">
<tbody>
<tr>
<td style="text-align: center;"><img alt="StatPress Community" src="<?php echo $imgdir;?>statcomm-community.png"></td>
<td style="text-align: right;">Go <a href="http://www.wpgetready.com/">support site</a>
/ Go to the <a href="http://forum.wpgetready.com/">forums</a></td>
</tr>
</tbody>
</table>
<br>
<div style="text-align: left;"><big><big><big><big>Shortcode
Subplugin Documentation</big></big></big></big><br>
<div style="text-align: right;">Release 1.0.1<br>
</div>
</div>
<br>
<div>
<h2>&nbsp;Welcome!<br>
</h2>
The Shortcode Subplugin allows you to take advantage of Statcomm plugin
core, making dynamic pages and posts&nbsp;according user browser and
geolocation. If you want to see it in action please refer to the <a href="http://statcomm-demo.wpgetready.com/shortcode-demo/" target="_blank">Demo Page</a>
<br>
<br>
<big>How does it work?</big><br><br>
Every time an user navigates in your site, Statcomm saves all the
available information in a custom option named <span style="font-weight: bold;">currentUser</span>. <br>
This subplugin is one way to take advantage of this data to control
content.<br>
<br>
<big><big>The shortcodes</big></big><br>
<br>
<big><span style="font-weight: bold;">Displaying
Information: sc_field shortcode</span></big><br>
<br>
<big style="color: rgb(51, 51, 255); font-weight: bold;">Syntaxis:
[sc_field
fieldname='fieldname']</big><br>
<br>
This shortcode allows you to show a simple data from the current user,
where <span style="font-weight: bold;">fieldname </span>could
be one of the following values:<br>
<br>
<table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2">
<tbody>
<tr>
<td style="font-weight: bold;"><big><big>Fieldname<br>
</big></big></td>
<td style="font-weight: bold;"><big><big>Meaning</big></big></td>

</tr>
<tr>
<td style="font-weight: bold;">date</td>
<td>Current date</td>

</tr>
<tr>
<td style="font-weight: bold;">time</td>
<td>Current time</td>

</tr>
<tr>
<td style="font-weight: bold;">ip</td>
<td>User IP</td>

</tr>
<tr>
<td style="font-weight: bold;">urlrequested</td>
<td>Url requested, usually the page the user navigates</td>

</tr>
<tr>
<td style="font-weight: bold;">agent</td>
<td>complete agent string, without decoding</td>

</tr>
<tr>
<td style="font-weight: bold;">referrer</td>
<td>Usually the url where the user comes from</td>

</tr>
<tr>
<td style="font-weight: bold;">search</td>
<td>Search string if the user comes from a search engine</td>

</tr>
<tr>
<td style="font-weight: bold;">nation</td>
<td>nation from the user comes</td>

</tr>
<tr>
<td style="font-weight: bold;">os</td>
<td>Operative System</td>

</tr>
<tr>
<td style="font-weight: bold;">browser</td>
<td>General name for user Browser</td>

</tr>
<tr>
<td style="font-weight: bold;">language</td>
<td>Default language in the browser settings</td>

</tr>
<tr>
<td style="font-weight: bold;">searchengine</td>
<td>Search engine name(if the user come from a search
engine)</td>

</tr>
<tr>
<td style="font-weight: bold;">spider</td>
<td>Spider name (this is not an user, this is a bot)</td>

</tr>
<tr>
<td style="font-weight: bold;">feed</td>
<td>?</td>

</tr>
<tr>
<td style="font-weight: bold;">user</td>
<td>User. This is valid only for logged users.</td>

</tr>
<tr>
<td style="font-weight: bold;">timestamp</td>
<td>timestamp to be stored on the database.</td>

</tr>
<tr>
<td style="font-weight: bold;">statuscode</td>
<td>Status code for the page, usually 200 (OK)</td>

</tr>
<tr>
<td style="font-weight: bold;">typ</td>
<td>?</td>

</tr>
<tr>
<td style="font-weight: bold;">ua_family</td>
<td>User Agent Family</td>

</tr>
<tr>
<td style="font-weight: bold;">ua_name</td>
<td>User Agent Name</td>

</tr>
<tr>
<td style="font-weight: bold;">ua_version</td>
<td>User Agent Version</td>

</tr>
<tr>
<td style="font-weight: bold;">ua_url</td>
<td>User Agent url is usually a link will take you to the
main browser company page.</td>

</tr>
<tr>
<td style="font-weight: bold;">ua_company</td>
<td>User Agent Company. Browser's author</td>

</tr>
<tr>
<td style="font-weight: bold;">ua_company_url</td>
<td>User Agent Company Url</td>

</tr>
<tr>
<td style="font-weight: bold;">ua_icon</td>
<td>url for displaying User Agent Icon.</td>

</tr>
<tr>
<td style="font-weight: bold;">ua_info_url</td>
<td>User Agent Info Url</td>

</tr>
<tr>
<td style="font-weight: bold;">os_family</td>
<td>Operative System Family</td>

</tr>
<tr>
<td style="font-weight: bold;">os_name</td>
<td>Operative System Name</td>

</tr>
<tr>
<td style="font-weight: bold;">os_url</td>
<td>Operative System Url</td>

</tr>
<tr>
<td style="font-weight: bold;">os_company</td>
<td>Operative System Company</td>

</tr>
<tr>
<td style="font-weight: bold;">os_company_url</td>
<td>Operative System Company Url: main site from the OS
makers.</td>

</tr>
<tr>
<td style="font-weight: bold;">os_icon</td>
<td>Operative System icon</td>

</tr>
<tr>
<td style="font-weight: bold;">country_code</td>
<td>Country Code (2 letters)</td>

</tr>
<tr>
<td style="font-weight: bold;">country_code3</td>
<td>Country Code (3 letters)</td>

</tr>
<tr>
<td style="font-weight: bold;">country_name</td>
<td>Contry Name (in english only)</td>

</tr>
<tr>
<td style="font-weight: bold;">region</td>
<td>Region Code (if available)</td>

</tr>
<tr>
<td style="font-weight: bold;">region_name</td>
<td>Region Name (if available)</td>

</tr>
<tr>
<td style="font-weight: bold;">city</td>
<td>City (if available and Maxmind enabled)</td>

</tr>
<tr>
<td style="font-weight: bold;">postal_code</td>
<td>Postal Code (if available)</td>

</tr>
<tr>
<td style="font-weight: bold;">latitude</td>
<td>Latitude (if Maxmind database is enabled)</td>

</tr>
<tr>
<td style="font-weight: bold;">longitude</td>
<td>Longitude (if Maxmind database is enabled)</td>

</tr>
<tr>
<td style="font-weight: bold;">area_code</td>
<td>Area Code (if available)</td>

</tr>
<tr>
<td style="font-weight: bold;">dma_code</td>
<td>DMA Code (if available)</td>

</tr>
<tr>
<td style="font-weight: bold;">metro_code</td>
<td>Metro Code (if available)</td>

</tr>
<tr>
<td style="font-weight: bold;">continent_code</td>
<td>Continent Code</td>

</tr>
<tr>
<td style="font-weight: bold;">continent_name</td>
<td>Continent Name (only englisj)</td>

</tr>

</tbody>
</table>
<br>
Most useful fieldnames would be: <span style="font-weight: bold;">ip,nation,os,browser,typ,ua_icon,so_icon,country_code,country_name,city,continent_code</span>
would be the most useful &nbsp;fieldnames.<br><br>Examples:<br><br>[sc_fieldname fieldname='browser'] &nbsp;will display the current user browser.<br><br>[sc_fieldname fieldname='ip'] will display the current user's IP.<br><br>
<big><span style="font-weight: bold;"><br>Controlling
information: sc_operator shortcode</span></big><br>
<br>
<big style="font-weight: bold;"><span style="color: rgb(51, 51, 255);">Syntaxis:
[sc_operator&nbsp; fieldname="country_code" operator="<span style="color: red;">(operation)</span>" values="<span style="color: red;">(value1,value2,value3,...)</span>"]
<span style="color: black;">CONTENT</span> </span></big><big style="font-weight: bold;"><span style="color: rgb(51, 51, 255);">[/sc_operator&nbsp;
field]</span></big><br>
<br>
Description:<br>
This shortcode <span style="font-weight: bold;"></span>allows
to display selectively information based on certain values provided on
the field <br>
<span style="font-weight: bold;">Atributes<br>
fieldaname: </span>The field name. Please see above table. if
the field is incorrect an errro will be displayed instead the field
value.<br>
<span style="font-weight: bold;"><br>
operator: &nbsp;</span> this <span style="font-weight: bold;">optional </span>&nbsp;field
is used to change the sc_operator behavior.<br>
Possible values:<br>
empty or missing = the operator will display if there is a match<br>
<span style="font-weight: bold;">not: </span>&nbsp;the
operator will display where there is <span style="font-weight: bold;">not </span>&nbsp;a
match<br>
<span style="font-weight: bold;">values</span>:&nbsp;<span style="font-weight: bold;"><span style="font-weight: bold;"><span style="font-weight: bold;"><span style="font-weight: bold;"></span></span></span></span>
possible matches comma delimited<br>
<br>
Examples:<br>
<br>
<big>[sc_operator fieldname="country_code" values ="US"]Welcome
from USA[/sc_operator]</big><br>
<br>
This shortcode will display the text Welcome from USA , only if the
user's country code matches US (United States).<br>
<br>
<big>[sc_operator fieldname="browser"
&nbsp;operator="not"&nbsp; values ="Internet
Explorer,Opera"]This demo is only for Chrome and Firefox
users[/sc_operator]</big><br>
<br>
This shortcode will display <span style="font-weight: bold;">'This
demo is only for Chrome and Firefox users</span>' <span style="font-weight: bold;">&nbsp;</span>for every
user not using Internet Explorer nor Opera. Note also this content will
be shown to others &nbsp;(Safari for example).<br>
<br>
<br>
<big><span style="font-weight: bold;">Nested
shortcode operation and other tips.<br>
</span></big><big><span style="font-weight: bold;"><span style="font-weight: bold;"></span></span><span style="font-weight: bold;"><span style="font-weight: bold;"></span></span></big>
<p>Even you <a href="http://codex.wordpress.org/Shortcode_API#Nested_Shortcodes" target="_blank">can't nest shortcode operations using the
same shortcode</a> this subplugin provides alternative ways.</p>
To deal with nesting, the subplugin provides de <span style="font-weight: bold;">sc_operator</span>,<span style="font-weight: bold;">sc_operator2</span> and <span style="font-weight: bold;">sc_operator3</span> shortcodes. These three shortcodes provides the same functionality to allow some degree of nesting.<br><br>Examples:<br><big>[sc_operator fieldname="country_code" values ="US"]<br></big>Your browser is &nbsp;&nbsp; <span style="font-weight: bold;">[sc_fieldname fieldname='browser'] </span>&nbsp;<br><big>[/sc_operator]</big><br><br>This
example will display the 'Your browser is ' and the browser the user is
currently using, but only for those user which country code is US.<br><br><big>[sc_operator fieldname="country_code" values ="US"]<br></big><big><br>&nbsp;&nbsp;&nbsp; [sc_operator2 fieldname="city" values ="Oregon"]</big><br>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; Your browser is &nbsp;&nbsp; <span style="font-weight: bold;">[sc_fieldname fieldname='browser'] </span>&nbsp;<br><big>&nbsp;&nbsp;&nbsp; [sc_operator2/]</big><br><br><big>&nbsp;&nbsp;&nbsp; [sc_operator2 fieldname="city" values ="Portland"]</big><br>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; Welcome newcomer &nbsp;&nbsp; <span style="font-weight: bold;">[sc_fieldname fieldname='browser'] </span>&nbsp;<br><big>&nbsp;&nbsp;&nbsp; [sc_operator2/]</big><br><br><big>&nbsp;&nbsp;&nbsp; [sc_operator2 fieldname="city" operator="not" values ="Oregon,Portland"]</big><br>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; Welcome newcomer &nbsp;&nbsp; <span style="font-weight: bold;">[sc_fieldname fieldname='browser'] </span>&nbsp;<br><big>&nbsp;&nbsp;&nbsp; [sc_operator2/]</big><br><br><br><big>[/sc_operator]</big><br><br>This
example will display custom content only for users from US and will
customize a mesage if he is from Oregon, Portland or none of them<br><br>Limitations and invalid cases<br><br>using
sc_operator,sc_operator2, and sc_operator3 gives you the chance to nest
to a 3-level deep. You can use indistinctly all three with the only
condition of avoiding nesting<br><br>Valid examples:<br>[sc_operator]<br>.....<br>&nbsp;&nbsp;&nbsp; [sc_operator2]<br>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; ....<br>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; [sc_operator3]....[/sc_operator3]<br>&nbsp;&nbsp;&nbsp; [/sc_operator2]<br>..<br>[/sc_operator]<br><br>This is also valid:<br><br>[sc_operator2]....<br>...[sc_operator]<br>&nbsp;&nbsp;&nbsp; ....<br>&nbsp;&nbsp;&nbsp; [sc_operator]<br>[/sc_operator2]<br><br>These examples are invalid:<br>[sc_operator]<br>&nbsp;&nbsp;&nbsp; [sc_operator]<br>&nbsp;&nbsp;&nbsp; ...<br>&nbsp;&nbsp;&nbsp; [sc_operator]<br>[/sc_operator]<br>This is invalid because we are nesting the same shortcode<br><br>[sc_operator]<br>&nbsp;&nbsp;&nbsp; [sc_operator2]<br>&nbsp;&nbsp;&nbsp; [/sc_operator]<br>[/sc_operator2]<br><br>This example is invalid because the closing order is incorrect. For more help about this topic, please see <a href="http://codex.wordpress.org/Shortcode_API#Nested_Shortcodes" target="_blank">the ShortCode API</a><br><br>To see some examples go to the <a href="http://statcomm-demo.wpgetready.com/shortcode-demo/" target="_blank">Demo Page</a>.<br><br></div>
