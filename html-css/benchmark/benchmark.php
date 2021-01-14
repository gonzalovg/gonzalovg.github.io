<<<<<<< HEAD
<?php

/**
 * PHP Script to benchmark PHP
 *
 * inspired by / thanks to:
 * - www.php-benchmark-script.com  (Alessandro Torrisi)
 * - www.webdesign-informatik.de
 *
 * @author @hm
 * @license MIT
 */
// -----------------------------------------------------------------------------
// Setup
// -----------------------------------------------------------------------------
set_time_limit(60); // 1 minuto // 2 minutos para pruebas remotas

$options = array();

// -----------------------------------------------------------------------------
// Main
// -----------------------------------------------------------------------------
// check performance

$benchmarkResult = test_benchmark($options);

// html output
echo "<!DOCTYPE html>\n<html lang='es'><head>\n";

echo "<style>
    table {
        color: #333; /* Lighten up font color */
        font-family: Helvetica, Arial, sans-serif; /* Nicer font */
        /*width: 640px;*/
        border-collapse:
        collapse; border-spacing: 0;
    }

    td, th {
        border: 1px solid #CCC; height: 30px;
    } /* Make cells a bit taller */

    th {
        background: #F3F3F3; /* Light grey background */
        font-weight: bold; /* Make sure they're bold */
    }

    td {
        background: #FAFAFA; /* Lighter grey background */
    }
    </style>
    </head>
    <body>";

echo array_to_html($benchmarkResult).'<p>';

echo "<h3>URL desde la que se ejecuta el script</h3>".dameURL();

//echo "<p><iframe src='https://whois.domaintools.com/solired.es' width='800px' height='600px'</iframe>";

//Search whois

echo '<p>Dame IP'.$_SERVER['SERVER_ADDR'].'<p>';
// echo '<form role="form" method="GET" target="_blank" action="https://whois.domaintools.com/go/'.dameURL().'">';
// echo '
                
//                 <button type="submit">Geolocalizar Servidor x URL
//                   </button>
                
//                 <input type="hidden" value="whois" name="service">
             
//             </form>';
    
// echo '<p><form role="form" method="GET" target="_blank" action="https://whois.domaintools.com/go/'.$_SERVER['SERVER_ADDR'].'">';
// echo '
                
//                 <button type="submit">Geolocalizar Servidor x IP
//                   </button>
                
//                 <input type="hidden" value="whois" name="service">
             
//             </form>';

// echo '<p><form role="form" method="GET" target="_blank" action="https://whois.domaintools.com/'.$_SERVER['SERVER_ADDR'].'">';
// echo '
                
//                 <button type="submit">Geolocalizar Servidor x IP (2)
//                   </button>
                
//                 <input type="hidden" value="whois" name="service">
             
//             </form>';

// echo '<p><form role="form" method="GET" target="_blank" action="https://whois.domaintools.com/'.$_SERVER['SERVER_ADDR'].'">';
// echo '
                
//                 <button type="submit">Geolocalizar Servidor x IP (3)
//                   </button>
                
               
             
//             </form>';

// echo "<p>
// <iframe src='https://whois.domaintools.com/".$_SERVER['SERVER_ADDR']." width='800px' height='600px'</iframe><p>";

   

echo "\n</body></html>";
exit;

// -----------------------------------------------------------------------------
// Benchmark functions
// -----------------------------------------------------------------------------

//Geo-Posicionamiento Server


function dameURL()
{
    $url="http://".$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
    return $url;
}

function test_benchmark($settings)
{
    $timeStart = microtime(true);

    $result = array();
    $result[''] = 'Benchmark script version 1.4';
    $result['sysinfo']['Fecha & Hora'] = date("d-M-Y //  H:i:s");
    $result['sysinfo']['Version de php'] = PHP_VERSION;
    $result['sysinfo']['Sistema operativo Servidor'] = PHP_OS;
    $result['sysinfo']['Nombre del servidor'] = $_SERVER['SERVER_NAME'];
    $result['sysinfo']['Ip del servidor'] = $_SERVER['SERVER_ADDR'];
    $result['sysinfo']['Geo Servidor'] = $_SERVER['HTTP_HOST'];


    test_math($result);
    test_string($result);
    test_loops($result);
    test_ifelse($result);
    if (isset($settings['db.host'])) {
        test_mysql($result, $settings);
    }

    $result['total'] = timer_diff($timeStart);
    return $result;
}

function test_math(&$result, $count = 99999)
{
    $timeStart = microtime(true);

    $mathFunctions = array("abs", "acos", "asin", "atan", "bindec", "floor", "exp", "sin", "tan", "pi", "is_finite", "is_nan", "sqrt");
    for ($i = 0; $i < $count; $i++) {
        foreach ($mathFunctions as $function) {
            call_user_func_array($function, array($i));
        }
    }
    $result['benchmark']['math'] = timer_diff($timeStart);
}

function test_string(&$result, $count = 99999)
{
    $timeStart = microtime(true);
    $stringFunctions = array("addslashes", "chunk_split", "metaphone", "strip_tags", "md5", "sha1", "strtoupper", "strtolower", "strrev", "strlen", "soundex", "ord");

    $string = 'el perro de San Roque no tiene rabo';
    for ($i = 0; $i < $count; $i++) {
        foreach ($stringFunctions as $function) {
            call_user_func_array($function, array($string));
        }
    }
    $result['benchmark']['string'] = timer_diff($timeStart);
}

function test_loops(&$result, $count = 999999)
{
    $timeStart = microtime(true);
    for ($i = 0; $i < $count; ++$i) {
    }
    $i = 0;
    while ($i < $count) {
        ++$i;
    }
    $result['benchmark']['loops'] = timer_diff($timeStart);
}

function test_ifelse(&$result, $count = 999999)
{
    $timeStart = microtime(true);
    for ($i = 0; $i < $count; $i++) {
        if ($i == -1) {
        } elseif ($i == -2) {
        } elseif ($i == -3) {
        }
    }
    $result['benchmark']['if-else'] = timer_diff($timeStart);
}


function test_mysql(&$result, $settings)
{
    $timeStart = microtime(true);

    $link = mysqli_connect($settings['db.host'], $settings['db.user'], $settings['db.pw']);
    $result['benchmark']['mysql']['connect'] = timer_diff($timeStart);
    //Descomentar si se quiere el test mysql
    // $arr_return['sysinfo']['Version de mysql (mysql_version)'] = '';

    mysqli_select_db($link, $settings['db.name']);
    $result['benchmark']['mysql']['select_db'] = timer_diff($timeStart);

    $dbResult = mysqli_query($link, 'SELECT VERSION() as version;');
    $arr_row = mysqli_fetch_array($dbResult);
    $result['sysinfo']['mysql_version'] = $arr_row['version'];
    $result['benchmark']['mysql']['query_version'] = timer_diff($timeStart);

    $query = "SELECT BENCHMARK(1000000,ENCODE('hello',RAND()));";
    $dbResult = mysqli_query($link, $query);
    $result['benchmark']['mysql']['query_benchmark'] = timer_diff($timeStart);

    mysqli_close($link);

    $result['benchmark']['mysql']['total'] = timer_diff($timeStart);
    return $result;
}

function timer_diff($timeStart)
{
    return number_format(microtime(true) - $timeStart, 3);
}

function array_to_html($array)
{
    $result = '';
    if (is_array($array)) {
        $result .= '<table>';
        foreach ($array as $k => $v) {
            $result .= "\n<tr><td>";
            $result .= '<strong>' . htmlentities($k) . "</strong></td><td>";
            $result .= array_to_html($v);
            $result .= "</td></tr>";
        }
        $result .= "\n</table>";
    } else {
        $result = htmlentities($array);
    }
    return $result;
}
=======
<?php

/**
 * PHP Script to benchmark PHP
 *
 * inspired by / thanks to:
 * - www.php-benchmark-script.com  (Alessandro Torrisi)
 * - www.webdesign-informatik.de
 *
 * @author @hm
 * @license MIT
 */
// -----------------------------------------------------------------------------
// Setup
// -----------------------------------------------------------------------------
set_time_limit(60); // 1 minuto // 2 minutos para pruebas remotas

$options = array();


// -----------------------------------------------------------------------------
// Main
// -----------------------------------------------------------------------------
// check performance

$benchmarkResult = test_benchmark($options);

// html output
echo "<!DOCTYPE html>\n<html lang='es'><head>\n";

echo "
<link rel='preconnect' href='https://fonts.gstatic.com'>
<link href='https://fonts.googleapis.com/css2?family=Anton&family=Roboto:wght@300&display=swap' rel='stylesheet'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<link href='../css/animate.css' rel='stylesheet' >
<meta name='author' content='Gonzalo Verdugo'>
<meta name='description' content='BENCHMARK sobre el rendimiento de mi servidor'>
<link rel='icon' type='image/icon' href='../../favicon.ico'>
<title>BENCHMARK</title>
<style>
    *{
    font-family:'Roboto', sans-serif;
    }

    h1,h2{
        font-family:'Anton',sans-serif;
        font-weight:300;
        margin:0;
       
    }

    table {
        color: #333; /* Lighten up font color */
        font-family: Helvetica, Arial, sans-serif; /* Nicer font */
        /*width: 640px;*/
        border-collapse:
        collapse; border-spacing: 0;
    }

    td, th {
        border: 1px solid #CCC; height: 30px;
    } /* Make cells a bit taller */

    th {
        background: #F3F3F3; /* Light grey background */
        font-weight: bold; /* Make sure they're bold */
    }

    td {
        background: #FAFAFA; /* Lighter grey background */
    }

  

    main{
        width: 80%;
        display:flex;
        margin:0 auto;
        flex-direction: column;
        align-items: center;
        


    
    }
    body{
        background-color: #eee;
        margin:0;
        
    }

    #graphs{
        width: 100%;
        display: flex;
        flex-direction: column;

        
    }

    #graphs-main{
   
 
        display:flex;
        justify-content:center;
        padding:3px;
    
    }


    .big-graph{
        width: 80%;
        padding:3px;
        
    }

    .small-graph{
   
        width:180px;
        padding:3px;
        margin: 5px 0;
       
    

    }

    #graphs-second{
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        padding:3px;
        flex-wrap: wrap;
     
    }

    #sysinfo{
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        margin:60px 0 0 0;

    }

    #id-info{
        width:100%;
        display: flex;
        flex-direction: row;
        justify-content: space-evenly;
        flex-wrap: wrap;
    }

    .id-info-data{
        width: 300px;
        margin:10px;
        
       
    }

    #general-info{
        width: 100%;
        display: flex;
        justify-content: center;
        flex-direction: row;
        flex-wrap: wrap;
        padding:3px;
  
    }

    .general-info-data{
       
        width: 260px;
        margin: 30px 20px 15px 20px;
        padding:3px;
        border-radius:12px;
        /*border:1px solid white;*/
        box-shadow: 1px 1px 1px 1px  #888888;
        padding: 20px;
        
    }

    i{
        font-size:2em;
    }

    .progress-container{
        height: 30px;
        width: 100%;
        background-color: white;
        border-radius:5px;

    }

    .progress-bar{
        height: 100%;
        
        border-radius:5px;
    }

    .text-center{
        text-align:center;
    
    }

    p{
        margin:0;
    }

    hr{
        color:#ffffff;
        
    }

    a{
        color:#17A589;
    }

    </style>
    <script src='https://kit.fontawesome.com/b228dc2ea7.js' crossorigin='anonymous'></script>

    </head>
    <body>";

    echo "<main>
        <h1>BENCHMARK</h1>
        <p class='text-center'><a href='../../index.html'>INICIO</a> || <a href='sketch-benchmark.pdf' target='blank'>WIREFRAME</a></p>
        <div id='graphs'>
    <div id='graphs-main'>
        <div class='big-graph'><h1 class='text-center'>TOTAL</h1>".print_total()."</div>
    </div>
    
    ";

    echo print_graphs(test_benchmark($options));



    echo "
   
    </div>

    <div id='sysinfo'>
        <h2>SYSINFO</h2>
        <div id='id-info'>
            <div class='id-info-data'><p class='text-center'><b>URL</b></p><p class='text-center'>".dameURL()."</p></div>
            <div class='id-info-data'><p class='text-center'><b>IP</b></p><p class='text-center'>".$_SERVER['SERVER_ADDR']."</p></div>
           
        </div>
       ";

echo array_to_html(data_benchmark());

echo "
    </div>

  

    


</main>";







//echo "<p><iframe src='https://whois.domaintools.com/solired.es' width='800px' height='600px'></iframe>";

//Search whois


// echo '<form role="form" method="GET" target="_blank" action="https://whois.domaintools.com/go/'.dameURL().'">';
// echo '
                
//                 <button type="submit">Geolocalizar Servidor x URL
//                   </button>
                
//                 <input type="hidden" value="whois" name="service">
             
//             </form>';
    
// echo '<p><form role="form" method="GET" target="_blank" action="https://whois.domaintools.com/go/'.$_SERVER['SERVER_ADDR'].'">';
// echo '
                
//                 <button type="submit">Geolocalizar Servidor x IP
//                   </button>
                
//                 <input type="hidden" value="whois" name="service">
             
//             </form>';

// echo '<p><form role="form" method="GET" target="_blank" action="https://whois.domaintools.com/'.$_SERVER['SERVER_ADDR'].'">';
// echo '
                
//                 <button type="submit">Geolocalizar Servidor x IP (2)
//                   </button>
                
//                 <input type="hidden" value="whois" name="service">
             
//             </form>';

// echo '<p><form role="form" method="GET" target="_blank" action="https://whois.domaintools.com/'.$_SERVER['SERVER_ADDR'].'">';
// echo '
                
//                 <button type="submit">Geolocalizar Servidor x IP (3)
//                   </button>
                
               
             
//             </form>';

// echo "<p>
// <iframe src='https://whois.domaintools.com/".$_SERVER['SERVER_ADDR']." width='800px' height='600px'</iframe><p>";

   

echo "\n</body></html>";
exit;

// -----------------------------------------------------------------------------
// Benchmark functions
// -----------------------------------------------------------------------------

//Geo-Posicionamiento Server


function dameURL()
{
    $url="http://".$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
    return $url;
}

function test_benchmark($settings)
{
    $timeStart = microtime(true);

    // $result = array();
    // $result[''] = 'Benchmark script version 1.4';
    // // $result['sysinfo']['Fecha & Hora'] = date("d-M-Y //  H:i:s");
    // // $result['sysinfo']['Version de php'] = PHP_VERSION;
    // // $result['sysinfo']['Sistema operativo Servidor'] = PHP_OS;
    // // $result['sysinfo']['Nombre del servidor'] = $_SERVER['SERVER_NAME'];
    // // $result['sysinfo']['Ip del servidor'] = $_SERVER['SERVER_ADDR'];
    // // $result['sysinfo']['Geo Servidor'] = $_SERVER['HTTP_HOST'];
    // // $result['icon']['Fecha & Hora'] = "<i class='far fa-clock'></i>";
    // $result['sysinfo']['<i class="far fa-clock"></i>'] = date("d-M-Y //  H:i:s");
    // $result['sysinfo']['<i class="fab fa-php"></i>'] = PHP_VERSION;
    // $result['sysinfo']['<i class="fab fa-battle-net"></i>'] = PHP_OS;
    // $result['sysinfo']['<i class="fas fa-laptop"></i>'] = $_SERVER['SERVER_NAME'];
    // $result['sysinfo']['<i class="fas fa-thumbtack"></i>'] = $_SERVER['SERVER_ADDR'];
    // $result['sysinfo']['<i class="fas fa-globe-europe"></i>'] = $_SERVER['HTTP_HOST'];
    
    $resultNumber=array();

    $resultNumber['math']= test_math($result);
    $resultNumber['string'] =test_string($result);
    $resultNumber['loops'] = test_loops($result);
    $resultNumber['if-else'] =test_ifelse($result);
    if (isset($settings['db.host'])) {
        test_mysql($resultNumber, $settings);
    }

    $total = timer_diff($timeStart);
    return $resultNumber;
}

function getTotalColor($total)
{
    $color = "";



    if ($total>=1) {
        $color="#E74C3C";
    } elseif ($total<1 && $total>=0.5) {
        $color = "#D4AC0D";
    } elseif ($total<0.5) {
        $color="#2ECC71";
    } else {
        $color="#eee";
    }

    return $color;
}

function getTotal()
{
    $timeStart = microtime(true);

    test_benchmark($options="");

    $total = timer_diff($timeStart);

   


    $total = timer_diff($timeStart) ;
   

    return $total;
}

function print_total()
{
    $genTotal = getTotal();
    return "<h1 class='text-center' style='color:".getTotalColor($genTotal).";'>". $genTotal."</h1>";
}


function data_benchmark()
{
    $resultData = array();

    // $result['sysinfo']['Fecha & Hora'] = date("d-M-Y //  H:i:s");
    // $result['sysinfo']['Version de php'] = PHP_VERSION;
    // $result['sysinfo']['Sistema operativo Servidor'] = PHP_OS;
    // $result['sysinfo']['Nombre del servidor'] = $_SERVER['SERVER_NAME'];
    // $result['sysinfo']['Ip del servidor'] = $_SERVER['SERVER_ADDR'];
    // $result['sysinfo']['Geo Servidor'] = $_SERVER['HTTP_HOST'];
    // $result['icon']['Fecha & Hora'] = "<i class='far fa-clock'></i>";
    $resultData['<i class="far fa-clock"></i>'] = date("d-M-Y //  H:i:s");
    $resultData['<i class="fab fa-php"></i>'] = PHP_VERSION;
    $resultData['<i class="fab fa-battle-net"></i>'] = PHP_OS;
    $resultData['<i class="fas fa-laptop"></i>'] = $_SERVER['SERVER_NAME'];
    $resultData['<i class="fas fa-thumbtack"></i>'] = $_SERVER['SERVER_ADDR'];
    $resultData['<i class="fas fa-globe-europe"></i>'] = $_SERVER['HTTP_HOST'];

    return $resultData;
}

function test_math(&$result, $count = 99999)
{
    $timeStart = microtime(true);

    $mathFunctions = array("abs", "acos", "asin", "atan", "bindec", "floor", "exp", "sin", "tan", "pi", "is_finite", "is_nan", "sqrt");
    for ($i = 0; $i < $count; $i++) {
        foreach ($mathFunctions as $function) {
            call_user_func_array($function, array($i));
        }
    }
    return timer_diff($timeStart);
    // $result['benchmark']['math'] = timer_diff($timeStart);
}

function test_string(&$result, $count = 99999)
{
    $timeStart = microtime(true);
    $stringFunctions = array("addslashes", "chunk_split", "metaphone", "strip_tags", "md5", "sha1", "strtoupper", "strtolower", "strrev", "strlen", "soundex", "ord");

    $string = 'el perro de San Roque no tiene rabo';
    for ($i = 0; $i < $count; $i++) {
        foreach ($stringFunctions as $function) {
            call_user_func_array($function, array($string));
        }
    }
    return timer_diff($timeStart);
    // $result['benchmark']['string'] = timer_diff($timeStart);
}

function test_loops(&$result, $count = 999999)
{
    $timeStart = microtime(true);
    for ($i = 0; $i < $count; ++$i) {
    }
    $i = 0;
    while ($i < $count) {
        ++$i;
    }
    return timer_diff($timeStart);
    //  = timer_diff($timeStart);
}

function test_ifelse(&$result, $count = 999999)
{
    $timeStart = microtime(true);
    for ($i = 0; $i < $count; $i++) {
        if ($i == -1) {
        } elseif ($i == -2) {
        } elseif ($i == -3) {
        }
    }
    return timer_diff($timeStart);
    // $result['benchmark']['if-else'] = timer_diff($timeStart);
}


function test_mysql(&$result, $settings)
{
    $timeStart = microtime(true);

    $link = mysqli_connect($settings['db.host'], $settings['db.user'], $settings['db.pw']);
    $result['benchmark']['mysql']['connect'] = timer_diff($timeStart);
    //Descomentar si se quiere el test mysql
    // $arr_return['sysinfo']['Version de mysql (mysql_version)'] = '';

    mysqli_select_db($link, $settings['db.name']);
    $result['benchmark']['mysql']['select_db'] = timer_diff($timeStart);

    $dbResult = mysqli_query($link, 'SELECT VERSION() as version;');
    $arr_row = mysqli_fetch_array($dbResult);
    $result['sysinfo']['mysql_version'] = $arr_row['version'];
    $result['benchmark']['mysql']['query_version'] = timer_diff($timeStart);

    $query = "SELECT BENCHMARK(1000000,ENCODE('hello',RAND()));";
    $dbResult = mysqli_query($link, $query);
    $result['benchmark']['mysql']['query_benchmark'] = timer_diff($timeStart);

    mysqli_close($link);

    $result['benchmark']['mysql']['total'] = timer_diff($timeStart);
    return $result;
}

function timer_diff($timeStart)
{
    return number_format(microtime(true) - $timeStart, 3);
}

function array_to_html($array)
{
    $result = '';
    if (is_array($array)) {
        $result .= '<div id="general-info">';
        // <div class='general-info-data'><i></i><span></span></div>
        foreach ($array as $k => $v) {
            $result.="<div class='general-info-data  animate__animated animate__backInDown animate__delay-1s'>";
            $result .=  "<h2 class='text-center'>".$k."</h2>";
            $result .= "<p class='text-center'>". array_to_html($v) . "</p>";
            $result .= "</div>";
        }
        $result .= "\n</div>";
    } else {
        $result = htmlentities($array);
    }
    // if (is_array($array)) {
    //     $result .= '<table>';
    //     foreach ($array as $k => $v) {
    //         $result .= "\n<tr><td>";
    //         $result .= $k. "</td><td>";
    //         $result .=array_to_html($v);
    //         $result .= "</td></tr>";
    //     }
    //     $result .= "\n</table>";
    // } else {
    //     $result = htmlentities($array);
    // }
    return $result;
}

function print_graphs($array)
{
    $result = '';
    if (is_array($array)) {
        $result .= '<div id="graphs-second">';
        $total = getTotal();
        foreach ($array as $k => $v) {
            $result.="<div class='small-graph'>";
           
            $result.= "<p class='text-center'><b>".strtoupper($k)."</b></p>";
            $result .= "<p class='text-center'> ". $v . "</p>";
            $result .= "<div class='progress-container  animate__animated animate__backInDown '>";

            $mediaNecesariaPorValor = 0.12;
            $color="";

            $valorEnBarra = round($v*100/$total);

            if ($v<$mediaNecesariaPorValor) {
                $color="#2ECC71";
            } elseif ($v>$mediaNecesariaPorValor&& $v<0.30) {
                $color="#D4AC0D";
            } elseif ($v>=0.30) {
                $color="#E74C3C";
            }


            $result.="<div class='progress-bar animate__animated animate__backInDown animate__delay-1s ' style='width:".$valorEnBarra ."%;background-color:".$color."'>";
            $result.="</div></div>";

            $result .= "</div>";
        }
        $result .= "\n</div>";
    } else {
        $result = htmlentities($array);
    }
    return $result;
}
>>>>>>> adead732e624ddeb7372c26d266ecf879010941f
