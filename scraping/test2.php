<?php
$linkPattern='/store\/ProductDetail.*onclick/ius';
$linkDeletePattern=array('<a href=\'','\' onclick=');
$subject="<a href='/store/ProductDetail.aspx?pcd=0404181203012&ccd=F1000131&wkcd=F1000114' onclick='' class=\"sclistanc\">";

preg_match($linkPattern,$subject,$array);
print_r($array);
?>
