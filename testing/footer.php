<?
$end = gettimeofday(true);//get the end time to track how long pages are taking to generate.
$time = round(($end[usec]-$start[usec])/1000000, 2);

print "<center><font size=\"-2\">Page generated in $time seconds.</font></center>\n";
//print information about a liscence, and where to get your own copy.
print "<p/>\n<center><font size=\"-2\">This database is powered by <a href=\"http://bdsm.sf.net\">Bowling Database/Statistics Management</a>.<br/>\nFreely available under the <a href=\"http://www.gnu.org/copyleft/lesser.html\">GNU LGPL</a>.</font></center>\n";
?>
</div><!--end of div main-->
</body>
</html>