<?
/*
Bowling Database/Statistics Management
Copyright (C) 2006 Jason Francis and BD/SM developement team

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
*/

$end = gettimeofday(true);//get the end time to track how long pages are taking to generate.
$time = round(($end[usec]-$start[usec])/1000000, 2);

print "<center><font size=\"-2\">Page generated in $time seconds.</font></center>\n";
//print information about a liscence, and where to get your own copy.
print "<p/>\n<center><font size=\"-2\">This database is powered by <a href=\"http://bdsm.sf.net\">Bowling Database/Statistics Management</a>.<br/>\nFreely available under the <a href=\"http://www.gnu.org/copyleft/lesser.html\">GNU LGPL</a>.</font></center>\n";
?>
</div><!--end of div main-->
</body>
</html>