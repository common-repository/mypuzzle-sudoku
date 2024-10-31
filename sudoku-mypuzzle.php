<?php
/*
Plugin Name: MyPuzzle - Sudoku
Plugin URI: http://blog.mypuzzle.org/sudoku-for-wordpress/
Description: Include a mypuzzle.org Sudoku in your blogs with just one shortcode. 
Version: 1.3.0
Author: tom@mypuzzle.org
Author URI: http://mypuzzle.org/
Notes    : Visible Copyrights and Hyperlink to mypuzzle.org required
*/


/*  Copyright 2012  tom@mypuzzle.org  (email : tom@mypuzzle.org)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Default Options
 */
function get_sud_mp_options ($default = false){
	$shc_default = array(
            'size' => '1',
            'level' => '1',
            'showlevel' => '1',
            'showhistory' => '1',
            'showtimer' => '1',
            'showprint' => '1',
            'language' => 'en',
            'showlink' => '0',
            'bgcolor' => '#ffffff'
            );
	if ($default) {
		update_option('shc_op', $shc_default);
		return $shc_default;
	}
	
	$options = get_option('shc_op');
	if (isset($options))
		return $options;
	update_option('shc_op', $shc_default);
	return $options;
}

/**
 * The Sortcode
 */
 
add_shortcode('sudoku-mp', 'sud_mp');

function sud_mp($atts) {
	global $post;
	$options = get_sud_mp_options();	
	
	$size = $options['size'];
	$level = $options['level'];
        $showlevel = $options['showlevel'];
        $showhistory = $options['showhistory'];
        $showtimer = $options['showtimer'];
        $showprint = $options['showprint'];        
	$language = $options['language'];
        $showlink = $options['showlink'];
        $bgcolor = $options['bgcolor'];

	extract(shortcode_atts(array(
                'size' => $size,
                'level' => $level,
                'showlevel' => $showlevel,
                'showhistory' => $showhistory,
                'showtimer' => $showtimer,
		'showprint' => $showprint,
		'language' => $language,
                'showlink' => $showlink,
                'bgcolor' => $bgcolor
	), $atts));
        
        $bgcolor = str_replace('#', '', $bgcolor);
        $showlink = "1";
        switch ($size)
        {
            case "1": 
                $iHeight = 390;
                if ($showlevel == "0" && $showhistory == "0") $iHeight -= 20;
                if ($showtimer == "0" && $showprint == "0") $iHeight -= 20;
                $output = "<div style='width:350px;'>";
                $output .= "<iframe src='http://mypuzzle.org/app/sudoku-plugin/sudoku_plugin.php?lang={$language}&bgcolor={$bgcolor}&size=1&level={$level}&showlevel={$showlevel}&showhist={$showhistory}&showtimer={$showtimer}&showprint={$showprint}' frameborder='0' width='380' height='{$iHeight}' scrolling='no'></iframe>";
                if ($showlink == "1") 
                    $output .= "<div style='float: left;font-size: 10px'><a href='http://mypuzzle.org/sudoku'>".getRndAnchor()."</a> by mypuzzle.org</div>";
                $output .= "</div>";
                return ($output);
                break;
            case "2": 
                $iHeight = 460;
                if ($showlevel == "0" && $showhistory == "0") $iHeight -= 20;
                if ($showtimer == "0" && $showprint == "0") $iHeight -= 20;
                $output = "<div style='width:440px;'>";
                $output .= "<iframe src='http://mypuzzle.org/app/sudoku-plugin/sudoku_plugin.php?lang={$language}&bgcolor={$bgcolor}&size=2&level={$level}&showlevel={$showlevel}&showhist={$showhistory}&showtimer={$showtimer}&showprint={$showprint}' frameborder='0' width='460' height='{$iHeight}' scrolling='no'></iframe>";        
                if ($showlink == "1") 
                    $output .= "<div style='float: left;font-size: 10px'><a href='http://mypuzzle.org/sudoku'>".getRndAnchor()."</a> by mypuzzle.org</div>";
                $output .= "</div>";
                return ($output);
                break;
        }
}
function getRndAnchor()
{
    $asKW = array('Online Sudoku','Sudoku Puzzle','Sudoku','Sudoku Puzzles'
        ,'Play Sudoku','Sudoku', 'Web Sudoku', 'Sudoku Online'
        , 'Daily Sudoku', 'Online Sudoku', 'Sudoku Puzzles', 'Easy Sudoku'
        , 'Free Online Sudoku', 'Sudoku', 'Sudoku Online', 'Printable Sudoku');
    $asHC = array('a', 'b', 'c', 'd', 'e', 'f', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');        
    $md5Str = strtolower(substr(strval(md5(strtolower($_SERVER['HTTP_HOST']))), 0, 1));    
    $idx = array_search($md5Str, $asHC);
    return($asKW[$idx]);
}

/**
 * Settings
 */  

add_action('admin_menu', 'sud_mp_set');

function sud_mp_set() {
	$plugin_page = add_options_page('MyPuzzle Sudoku', 'MyPuzzle Sudoku', 'administrator', 'sudoku-mp', 'sud_mp_options_page');		
 }

function sud_mp_options_page() {

	$options = get_sud_mp_options();
	
    if(isset($_POST['Restore_Default']))	$options = get_sud_mp_options(true);	?>

	<div class="wrap">   
	
	<h2><?php _e("MyPuzzle - Sudoku Settings") ?></h2>
	
	<?php 

	if(isset($_POST['Submit'])){
                $newoptions['showlink'] = isset($_POST['showlink'])?$_POST['showlink']:$options['showlink'];
     		$newoptions['size'] = isset($_POST['size'])?$_POST['size']:$options['size'];
     		$newoptions['level'] = isset($_POST['level'])?$_POST['level']:$options['level'];
                $newoptions['showlevel'] = isset($_POST['showlevel'])?$_POST['showlevel']:$options['showlevel'];
                $newoptions['showhistory'] = isset($_POST['showhistory'])?$_POST['showhistory']:$options['showhistory'];
                $newoptions['showtimer'] = isset($_POST['showtimer'])?$_POST['showtimer']:$options['showtimer'];
                $newoptions['showprint'] = isset($_POST['showprint'])?$_POST['showprint']:$options['showprint'];
                $newoptions['bgcolor'] = isset($_POST['bgcolor'])?$_POST['bgcolor']:$options['bgcolor'];
 
                $newoptions['language'] = isset($_POST['language'])?$_POST['language']:$options['language'];
	
                if ( $options != $newoptions ) {
                        $options = $newoptions;
                        update_option('shc_op', $options);			
                }

 	} 

	if(isset($_POST['Use_Default'])){
        update_option('shc_op', $options);
    }
        $showlink = $options['showlink'];
        $size = $options['size'];
	$level = $options['level'];
        $showlevel = $options['showlevel'];
        $showhistory = $options['showhistory'];
        $showtimer = $options['showtimer'];
        $showprint = $options['showprint'];
        $bgcolor = $options['bgcolor'];
        
	$language = $options['language'];
	
	?>
        <form method="POST" name="options" target="_self" enctype="multipart/form-data">
	<h3><?php _e("Sudoku Parameters") ?></h3>
	
	<p><?php _e("If you are missing something or want language adjustments please, write tom at mypuzzle.org.") ?></p>
	
        <table width="" border="0" cellspacing="10" cellpadding="0">
            <tr>
                <td width="100">
                    Language
                </td>
                <td>
                    <select name="language" id="language" style="width: 100px">
                            <option value="en"<?php echo ($language == 'en' ? " selected" : "") ?>><?php echo _e("English") ?></option>
                            <option value="fr"<?php echo ($language == 'fr' ? " selected" : "") ?>><?php echo _e("French") ?></option>
                            <option value="de"<?php echo ($language == 'de' ? " selected" : "") ?>><?php echo _e("German") ?></option>
                            <option value="it"<?php echo ($language == 'it' ? " selected" : "") ?>><?php echo _e("Italian") ?></option>
                            <option value="es"<?php echo ($language == 'es' ? " selected" : "") ?>><?php echo _e("Spanisch") ?></option>
                            <option value="se"<?php echo ($language == 'se' ? " selected" : "") ?>><?php echo _e("Swedish") ?></option>                            
                    </select>
                </td>
                <td width="500">
                    Select the plugin language.
                </td>
            </tr>
            <tr>
                <td width="100">
                    Size
                </td>
                <td>
                    <select name="size" id="size" style="width: 100px">
                            <option value="1"<?php echo ($size == 1 ? " selected" : "") ?>><?php echo _e("Small") ?></option>
                            <option value="2"<?php echo ($size == 2 ? " selected" : "") ?>><?php echo _e("Default") ?></option>
                    </select>
                </td>
                <td width="500">
                    The size of the sudoku. Right now there are only two options.
                </td>
            </tr>
            <tr>
                <td width="50">
                    Default Level
                </td>
                <td>
                    <select name="level" id="level" style="width: 100px">
                            <option value="1"<?php echo ($level == 1 ? " selected" : "") ?>><?php echo _e("Simple") ?></option>
                            <option value="2"<?php echo ($level == 2 ? " selected" : "") ?>><?php echo _e("Easy") ?></option>
                            <option value="3"<?php echo ($level == 3 ? " selected" : "") ?>><?php echo _e("Mild") ?></option>
                            <option value="4"<?php echo ($level == 4 ? " selected" : "") ?>><?php echo _e("Moderate") ?></option>
                            <option value="5"<?php echo ($level == 5 ? " selected" : "") ?>><?php echo _e("Hart") ?></option>
                            <option value="6"<?php echo ($level == 6 ? " selected" : "") ?>><?php echo _e("Very Hard") ?></option>
                            <option value="7"<?php echo ($level == 7 ? " selected" : "") ?>><?php echo _e("Diabolic") ?></option>
                    </select>
                </td>
                <td width="200">
                    The difficulty the sudoku starts with.
                </td>
            </tr>
            <tr>
                <td width="100">
                    Show Level
                </td>
                <td>
                    <select name="showlevel" id="showlevel" style="width: 100px">
                            <option value="1"<?php echo ($showlevel == 1 ? " selected" : "") ?>><?php echo _e("Yes") ?></option>
                            <option value="0"<?php echo ($showlevel == 0 ? " selected" : "") ?>><?php echo _e("No") ?></option>
                    </select>
                </td>
                <td width="200">
                    Whether the user can chose from different levels.
                </td>
            </tr>
            <tr>
                <td width="100">
                    Show History
                </td>
                <td>
                    <select name="showhistory" id="showhistory" style="width: 100px">
                            <option value="1"<?php echo ($showhistory == 1 ? " selected" : "") ?>><?php echo _e("Yes") ?></option>
                            <option value="0"<?php echo ($showhistory == 0 ? " selected" : "") ?>><?php echo _e("No") ?></option>
                    </select>
                </td>
                <td width="200">
                    Offers a list of the last ten days Sudoku.
                </td>
            </tr>
            <tr>
                <td width="100">
                    Show Timer
                </td>
                <td>
                    <select name="showtimer" id="showtimer" style="width: 100px">
                            <option value="1"<?php echo ($showtimer == 1 ? " selected" : "") ?>><?php echo _e("Yes") ?></option>
                            <option value="0"<?php echo ($showtimer == 0 ? " selected" : "") ?>><?php echo _e("No") ?></option>
                    </select>
                </td>
                <td width="200">
                    If you want a time running on the bottom.
                </td>
            </tr>
            <tr>
                <td width="100">
                    Show Print Button
                </td>
                <td>
                    <select name="showprint" id="showprint" style="width: 100px">
                            <option value="1"<?php echo ($showprint == 1 ? " selected" : "") ?>><?php echo _e("Yes") ?></option>
                            <option value="0"<?php echo ($showprint == 0 ? " selected" : "") ?>><?php echo _e("No") ?></option>
                    </select>
                </td>
                <td width="200">
                    If you want to enable the user to print the Sudoku.
                </td>
            </tr>
            <tr>
                <td width="100">
                    Background Color
                </td>
                <td>
                    <input style="width: 100px" type="text" name="bgcolor" value="<?php echo ($bgcolor); ?>">
                </td>
                <td width="200">
                    The primary background-color.
                </td>
            </tr>
        </table>
        
        <p class="submit">
            <input type="submit" name="Submit" value="Update" class="button-primary" />
        </p>
        </form>
    </div>


<?php } 

