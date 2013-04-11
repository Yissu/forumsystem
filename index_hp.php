<?PHP
    error_reporting(0);
    //require 'inc/ipblock.class.php';
    //$_SERVER['REMOTE_ADDR']		= $_SERVER["HTTP_CF_CONNECTING_IP"];
    
    //new IPBlock($_SERVER['REMOTE_ADDR']);
    if(!file_exists('./inc/config.inc.php'))
    {
      header('Location: install.php');
    }

    session_name("m2_ascent2");
    session_start();

    require("./inc/config.inc.php");
    require("./inc/rights.inc.php");
    require("./inc/functions.inc.php");
    require("./inc/page_informations.php");

    $sqlForum = mysql_connect(SQL_FORUM_HOST, SQL_FORUM_USER, SQL_FORUM_PASS);
    $sqlHp = mysql_connect(SQL_HP_HOST, SQL_HP_USER, SQL_HP_PASS);
    $sqlServ = mysql_connect(SQL_HOST, SQL_USER, SQL_PASS);

    if(!is_resource($sqlServ) OR !is_resource($sqlHp) OR !is_resource($sqlForum)) {
      exit('Es tut uns leid, es konnt keine Verbindung zum Server
          hergestellt werden.');
    }
    
    mysql_select_db(SQL_FORUM_DB, $sqlForum);
    
    //mysql_select_db(SQL_FORUM_DB, $sqlForum);
    require("./inc/head.inc.php");

    $strings = array("Oldschool", "Join Us", "We rise to the Top");

    $rand = rand(0, count($strings) - 1);

    $title = $strings[$rand];
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Ascent2 - <?=$strings[$rand]?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="css/main.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js" type="text/javascript"></script>
        <link rel="shortcut icon" href="fav.png" type="image/png" />
    </head>
    <body>
 <div class="page-all">
            <?php if(empty($_SESSION["user_id"])) { ?>
            <div class="navigation">
                <div class="login">
                    <form method="post" action="index.php?s=login">
                        <input type="text" name="userid" id="username" value="Benutzername" />
                        <input type="password" name="userpass" id="password" value="Passwort" />
                        <input type="submit" id="login" value="Login" />
                        <input type="hidden" name="sent" value="login" />
                    </form>
                    <div class="links">
                        <a href="index.php?s=register">Registrieren</a><br />
                        <a href="index.php?s=pwchange">Passwort vergessen?</a>
                    </div>
                </div>
            <?php } else { ?>
            <div class="navigation only-navi">
            <?php } ?>
                <div class="menu">
                    <ul>
                        <li class="small"><a href="index.php?s=home"><img src="img/home.png" /></a></li>
                        <li class="sep"></li>
                        <li><a href="index.php?s=register"><img src="img/font/menu_registrieren.png" /></a></li>
                        <li class="sep"></li>
                        <li><a href="index.php?s=downloads"><img src="img/font/menu_downloads.png" /></a></li>
                        <li class="sep"></li>
                        <li><a href="index.php?s=rankings"><img src="img/font/menu_rangliste.png" /></a></li>
                        <li class="sep"></li>
                        <li><a href="index.php?s=grankings"><img src="img/font/menu_gilden.png" /></a></li>
                        <li class="sep"></li>
                        <li><a href="board.ascent2.net"><img src="img/font/menu_forum.png" /></a></li>
                        <li class="sep"></li>
                        <li><a href="ts3server://127.0.0.1"><img src="img/font/menu_teamspeak.png" /></a></li>
                    </ul>
                </div><div style="clear: both;"></div>
                <div class="logo">
                </div>
            </div>
            <div class="downvote">
                <div class="container">
                    <a href="#"><div class="download"></div></a>
                    <a href="#"><div class="vote4us"></div></a>
                </div>
            </div>
            <div class="page-wrapper">
                <div class="left column">
                    <?php if(!empty($_SESSION["user_id"])) { ?>
                    <div class="box">
                        <div class="title"><img class="icon" src="img/icons/control.png" /><img class="font" src="img/font/box_control_panel.png" /></div>
                        <div class="content">
                            <img src="img/icons/dot.png" /> <a href="index.php?s=charaktere">Charakterliste</a><br />
                            <img src="img/icons/dot.png" /> <a href="index.php?s=itemshop">Itemshop</a><br />
                            <img src="img/icons/dot.png" /> <a href="index.php?s=spenden">Spenden</a><br />
                            <img src="img/icons/dot.png" /> <a href="index.php?s=vote">Voteshop</a><br />
                            <img src="img/icons/dot.png" /> <a href="index.php?s=vote4us">Vote 4 Coins</a><br />
                            <img src="img/icons/dot.png" /> <a href="index.php?s=passwort">Daten ändern</a><br />
                            <?php if($_SESSION['user_admin']>0) { ?>
                            <img src="img/icons/dot.png" /> <a href="index.php?s=admin">Adminbereich</a><br />
                            <? } ?>
                            <img src="img/icons/dot.png" /> <a href="index.php?s=logout">Ausloggen</a><br /><br />
                        </div>
                        <div class="footer-with-info">
                            <p>Du hast <b><?=number_format($_SESSION['user_coins'], 0, ",", ".")?></b> Coins<br />
                            Und <b><?=number_format($_SESSION['user_vote_coins'], 0, ",", ".")?></b> Vote Coins<br />
                            Registriert seit <b><?=$_SESSION['user_create_time']?></b>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="box">
                        <div class="title"><img class="icon" src="img/icons/team.png" /><img class="font" src="img/font/box_das_team.png" /></div>
                        <div class="content">
                            <?php
                                $exe = mysql_query("SELECT `online`, `name` FROM `" . SQL_HP_DB . "`.`team`;");
                                for($i = 0; $i < mysql_num_rows($exe); $i++) {
                                    if(mysql_result($exe, $i, "online") == 0) {
                                        echo '<img src="img/icons/offline.png" /> ' . mysql_result($exe, $i, "name") . '<br />';
                                    } else {
                                        echo '<img src="img/icons/online.png" /> ' . mysql_result($exe, $i, "name") . '<br />';
                                    }
                                }
                            ?>
                        </div>
                        <div class="footer"></div>
                    </div>
                </div>
                <div class="middle">
                <?PHP
                    if(isset($_GET['s']) && !empty($_GET['s']))
                    {
                        if(file_exists("./pages/".$_GET['s'].".php")) 
                        {
                            include("./pages/".$_GET['s'].".php");
                        }
                        else {
                            include("./pages/home.php");
                        }
                    } else 
                    {
                        include("./pages/home.php");
                    }
                ?>    
                </div>
                <div class="right column">
                    <div class="box">
                        <div class="title"><img class="icon" src="img/icons/status.png" /><img class="font" src="img/font/box_status_statistik.png" /></div>
                        <?php require("status.php"); ?>
                    </div>
                    <div class="box">
                        <div class="title"><img class="icon" src="img/icons/top.png" /><img class="font" src="img/font/box_top_10.png" /></div>
                        <div class="content nopadding">
                            <?php require("top10.php"); ?>
                        </div>
                        <div class="footer"></div>
                    </div>
                </div><div style="clear: both"></div>
                <div class="copyright">
                    <div class="inner">
                        <img src="img/font/copyright_1.png" />
                        <a href="mailto:mail.noart@web.de"><img src="img/font/copyright_2.png" /></a>
                        <img src="img/font/copyright_3.png" />
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<?PHP
  mysql_close();
?>