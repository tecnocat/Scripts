<?php /* UTF-8 Verified (áéíóú) */

// $Id$

$libro = $data['libro']  ? $data['libro']  : '';
$pagina = $data['pagina'] ? $data['pagina'] : '';
$service = $data['service'];
$baseurl = $data['baseurl'];
$flexurl = $data['flexurl'];
$flashvars = array(
  'libro'   => $libro,
  'pagina'  => $pagina,
  'service' => $service,
  'baseurl' => $baseurl,
  'flexurl' => $flexurl,
);
foreach ($flashvars as $var => $val) {
  $flashVars .= '&' . $var . '=' . $val;
}
/* HTML */
?>
<html lang="es">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="<?php echo $flexurl ?>history/history.css" />
    <title>eBook</title>
    <script src="<?php echo $flexurl ?>AC_OETags.js" type="text/javascript"></script>
    <script src="<?php echo $flexurl ?>history/history.js" type="text/javascript"></script>
    <style>
      body { margin: 0px; overflow:hidden }
    </style>
    <script type="text/javascript">
      <!--//--><![CDATA[//><!--
      var requiredMajorVersion = 9;
      var requiredMinorVersion = 0;
      var requiredRevision = 124;
      //--><!]]>
    </script>
  </head>
  <body scroll="no">
    <script type="text/javascript">
      <!--//--><![CDATA[//><!--
      var hasProductInstall = DetectFlashVer(6, 0, 65);
      var hasRequestedVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
      if ( hasProductInstall && !hasRequestedVersion ) {
        var MMPlayerType = (isIE == true) ? "ActiveX" : "PlugIn";
        var MMredirectURL = window.location;
        document.title = document.title.slice(0, 47) + " - Flash Player Installation";
        var MMdoctitle = document.title;
        AC_FL_RunContent(
          "src", "<?php echo $flexurl ?>playerProductInstall",
          "FlashVars", "MMredirectURL="+MMredirectURL+'&MMplayerType='+MMPlayerType+'&MMdoctitle='+MMdoctitle+"",
          "width", "100%",
          "height", "100%",
          "align", "middle",
          "id", "<?php echo $data['swf'] ?>",
          "quality", "high",
          "bgcolor", "#ffffff",
          "name", "<?php echo $data['swf'] ?>",
          "allowScriptAccess","sameDomain",
          "type", "application/x-shockwave-flash",
          "pluginspage", "http://www.adobe.com/go/getflashplayer"
        );
      } else if (hasRequestedVersion) {
        AC_FL_RunContent(
            "src", "<?php echo $flexurl . $data['swf'] ?>",
            "width", "100%",
            "height", "100%",
            "align", "middle",
            "id", "<?php echo $data['swf'] ?>",
            "quality", "high",
            "bgcolor", "#ffffff",
            "name", "<?php echo $data['swf'] ?>",
            "allowScriptAccess","sameDomain",
            "allowFullScreen", "true",
            "flashVars","vars=true<?php echo $flashVars ?>",
            "type", "application/x-shockwave-flash",
            "pluginspage", "http://www.adobe.com/go/getflashplayer"
        );
        } else {
          var alternateContent = 'Alternate HTML content should be placed here. '
          + 'This content requires the Adobe Flash Player. '
          + '<a href=http://www.adobe.com/go/getflash/>Get Flash</a>';
          document.write(alternateContent);
        }
      //--><!]]>
    </script>
    <noscript>
      <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
        id="<?php echo $data['swf'] ?>" width="100%" height="100%"
        codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
        <param name="movie" value="<?php echo $flexurl . $data['swf'] ?>.swf" />
        <param name="quality" value="high" />
        <param name="bgcolor" value="#ffffff" />
        <param name="allowScriptAccess" value="sameDomain" />
        <param name="flashVars" value="vars=true<?php echo $flashVars ?>" />
        <param name="allowFullScreen" value="true" />
        <embed src="<?php echo $data['swf'] ?>.swf" quality="high" bgcolor="#ffffff"
          width="100%" height="100%" name="<?php echo $data['swf'] ?>" align="middle"
          play="true"
          loop="false"
          quality="high"
          allowScriptAccess="sameDomain"
          allowFullScreen="true"
          type="application/x-shockwave-flash"
          flashVars="vars=true<?php echo $flashVars ?>"
          pluginspage="http://www.adobe.com/go/getflashplayer">
        </embed>
      </object>
    </noscript>
  </body>
</html>