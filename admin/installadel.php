<?php
include( "config.inc.php" );



$credittextid = $mysql->echo_one( "select id from ppc_publisher_credits order by id desc limit 0,1" );
//echo __Func010__( );

/*
if ( $flag_ppc_ad_block == 0 )
{
    if ( $flag_ppc_banner_sizes == 0 )
    {
        mysql_query( "INSERT INTO `ppc_ad_block` (`id`, `width`, `height`, `ad_type`, `max_size`, `orientaion`, `title_font`, `title_size`, `title_color`, `desc_font`, `desc_size`, `desc_color`, `url_font`, `url_size`, `url_color`, `bg_color`, `credit_font`, `credit_text_border_color`, `no_of_text_ads`, `credit_text_alignment`, `credit_text_positioning`, `allow_publishers`, `border_type`, `status`, `ad_font_weight`, `ad_title_decoration`, `ad_desc_font_weight`, `ad_desc_decoration`, `ad_disp_url_font_weight`, `ad_disp_url_decoration`, `credit_text_font_weight`, `credit_text_decoration`, `ad_block_name`, `credit_text`, `allow_title_color`, `allow_desc_color`, `allow_disp_url_color`, `allow_bg_color`, `allow_credit_color`, `allow_bordor_type`, `line_height` ) VALUES\r\n\t\t(13, 728, 90, 3, 100, 2, 'Arial, Helvetica, sans-serif', 12, '#000099', 'Arial, Helvetica, sans-serif', 12, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 3, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Leaderboard', '{$credittextid}', 1, 1, 1, 1, 1, 1,15),\r\n\t\t(14, 468, 60, 2, 100, 2, 'Arial, Helvetica, sans-serif', 12, '#000099', 'Arial, Helvetica, sans-serif', 12, '#0F0F0F', 'Arial, Helvetica, sans-serif', 10, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 1, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Banner', '{$credittextid}', 1, 1, 1, 1, 1, 1,15),\r\n\t\t(15, 160, 60, 2, 100, 2, 'Arial, Helvetica, sans-serif', 14, '#000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 14, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 1, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Half Banner', '{$credittextid}', 1, 1, 1, 1, 1, 1,15),\r\n\t\t(16, 120, 600, 3, 100, 1, 'Arial, Helvetica, sans-serif', 12, '#000099', 'Arial, Helvetica, sans-serif', 12, '#0F0F0F', 'Arial, Helvetica, sans-serif', 10, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 4, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Skyscraper', '{$credittextid}', 1, 1, 1, 1, 1, 1,15),\r\n\t\t(17, 160, 600, 3, 100, 1, 'Arial, Helvetica, sans-serif', 14, '#000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 4, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Wide Skyscraper', '{$credittextid}', 1, 1, 1, 1, 1, 1,15),\r\n\t\t(18, 120, 240, 2, 100, 1, 'Arial, Helvetica, sans-serif', 14, '#000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 1, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Vertical Banner', '{$credittextid}', 1, 1, 1, 1, 1, 1,15),\r\n\t\t(19, 336, 280, 3, 100, 1, 'Arial, Helvetica, sans-serif', 14, '#000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 3, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Large Rectangle', '{$credittextid}', 1, 1, 1, 1, 1, 1,15),\r\n\t\t(20, 300, 250, 3, 100, 1, 'Arial, Helvetica, sans-serif', 14, '#000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 3, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Medium Rectangle', '{$credittextid}', 1, 1, 1, 1, 1, 1,15),\r\n\t\t(21, 250, 250, 3, 100, 1, 'Arial, Helvetica, sans-serif', 14, '#000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 2, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Square', '{$credittextid}', 1, 1, 1, 1, 1, 1,15),\r\n\t\t(22, 200, 200, 3, 100, 1, 'Arial, Helvetica, sans-serif', 16, '#000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 14, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 1, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Small Square', '{$credittextid}', 1, 1, 1, 1, 1, 1,15),\r\n\t\t(23, 180, 150, 3, 100, 1, 'Arial, Helvetica, sans-serif', 14, '#000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 1, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Small Rectangle', '{$credittextid}', 1, 1, 1, 1, 1, 1,15),\r\n\t\t(24, 125, 125, 2, 100, 1, 'Arial, Helvetica, sans-serif', 14, '#000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 14, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 1, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Button', '{$credittextid}', 1, 1, 1, 1, 1, 1,15);" );
        echo __Func010__( );
    }
    else
    {
        $lastbannerid = $mysql->echo_one( "select id from ppc_banner_sizes order by id desc limit 0,1" );
        $existing_banners = mysql_query( "select * from ppc_banner_sizes" );
        $new_blocks = array( );
        $new_blocks[0] = array( 728, 90, 3, 2, 3, "Leaderboard" );
        $new_blocks[1] = array( 468, 60, 2, 2, 1, "Banner" );
        $new_blocks[2] = array( 160, 60, 2, 2, 1, "Half Banner" );
        $new_blocks[3] = array( 120, 600, 3, 1, 4, "Skyscraper" );
        $new_blocks[4] = array( 160, 600, 3, 1, 4, "Wide Skyscraper" );
        $new_blocks[5] = array( 120, 240, 2, 1, 1, "Vertical Banner" );
        $new_blocks[6] = array( 336, 280, 3, 1, 3, "Large Rectangle" );
        $new_blocks[7] = array( 300, 250, 3, 1, 3, "Medium Rectangle" );
        $new_blocks[8] = array( 250, 250, 3, 1, 2, "Square" );
        $new_blocks[9] = array( 200, 200, 3, 1, 1, "Small Square" );
        $new_blocks[10] = array( 180, 150, 3, 1, 1, "Small Rectangle" );
        $new_blocks[11] = array( 125, 125, 2, 1, 1, "Button" );
        $i = 0;
        while ( $i < __Func016__( $new_blocks ) )
        {
            __Func017__( $existing_banners, 0 );
            $block_found = 0;
            while ( $exisitng_banner_row = __Func011__( $existing_banners ) )
            {
                if ( $exisitng_banner_row[1] == $new_blocks[$i][0] && $exisitng_banner_row[2] == $new_blocks[$i][1] && $exisitng_banner_row[3] == 100 )
                {
                    mysql_query( "INSERT INTO `ppc_ad_block` (`id`, `width`, `height`, `ad_type`, `max_size`, `orientaion`, `title_font`, `title_size`, `title_color`, `desc_font`, `desc_size`, `desc_color`, `url_font`, `url_size`, `url_color`, `bg_color`, `credit_font`, `credit_text_border_color`, `no_of_text_ads`, `credit_text_alignment`, `credit_text_positioning`, `allow_publishers`, `border_type`, `status`, `ad_font_weight`, `ad_title_decoration`, `ad_desc_font_weight`, `ad_desc_decoration`, `ad_disp_url_font_weight`, `ad_disp_url_decoration`, `credit_text_font_weight`, `credit_text_decoration`, `ad_block_name`, `credit_text`, `allow_title_color`, `allow_desc_color`, `allow_disp_url_color`, `allow_bg_color`, `allow_credit_color`, `allow_bordor_type`, `line_height`) VALUES\r\n\t\t({$exisitng_banner_row['0']}, {$exisitng_banner_row['1']}, {$exisitng_banner_row['2']}, ".$new_blocks[$i][2].", {$exisitng_banner_row['3']}, ".$new_blocks[$i][3].", 'Arial, Helvetica, sans-serif', 12, '#000099', 'Arial, Helvetica, sans-serif', 12, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', ".$new_blocks[$i][4].", 1, 0, 1, 1, 1, 2, 2, 1, 1, 1, 1, 2, 1, '".$new_blocks[$i][5]."', '{$credittextid}', 1, 1, 1, 1, 1, 1,15);" );
                    echo __Func010__( );
                    $block_found = 1;
                    break;
                }
            }
            if ( $block_found == 0 )
            {
                ++$lastbannerid;
                mysql_query( "INSERT INTO `ppc_ad_block` (`id`, `width`, `height`, `ad_type`, `max_size`, `orientaion`, `title_font`, `title_size`, `title_color`, `desc_font`, `desc_size`, `desc_color`, `url_font`, `url_size`, `url_color`, `bg_color`, `credit_font`, `credit_text_border_color`, `no_of_text_ads`, `credit_text_alignment`, `credit_text_positioning`, `allow_publishers`, `border_type`, `status`, `ad_font_weight`, `ad_title_decoration`, `ad_desc_font_weight`, `ad_desc_decoration`, `ad_disp_url_font_weight`, `ad_disp_url_decoration`, `credit_text_font_weight`, `credit_text_decoration`, `ad_block_name`, `credit_text`, `allow_title_color`, `allow_desc_color`, `allow_disp_url_color`, `allow_bg_color`, `allow_credit_color`, `allow_bordor_type`,`line_height` ) VALUES\r\n\t\t({$lastbannerid}, ".$new_blocks[$i][0].", ".$new_blocks[$i][1].", ".$new_blocks[$i][2].", 100, ".$new_blocks[$i][3].", 'Arial, Helvetica, sans-serif', 12, '#000099', 'Arial, Helvetica, sans-serif', 12, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', ".$new_blocks[$i][4].", 1, 0, 1, 1, 1, 2, 2, 1, 1, 1, 1, 2, 1, '".$new_blocks[$i][5]."', '{$credittextid}', 1, 1, 1, 1, 1, 1,15);" );
                echo __Func010__( );
            }
            ++$i;
        }
        __Func017__( $existing_banners, 0 );
        while ( $exisitng_banner_row = __Func011__( $existing_banners ) )
        {
            $block_found = 0;
            $i = 0;
            while ( $i < __Func016__( $new_blocks ) )
            {
                if ( $exisitng_banner_row[1] == $new_blocks[$i][0] && $exisitng_banner_row[2] == $new_blocks[$i][1] && $exisitng_banner_row[3] == 100 )
                {
                    $block_found = 1;
                    break;
                }
                ++$i;
            }
            if ( $block_found == 0 )
            {
                mysql_query( "INSERT INTO `ppc_ad_block` (`id`, `width`, `height`, `ad_type`, `max_size`, `orientaion`, `title_font`, `title_size`, `title_color`, `desc_font`, `desc_size`, `desc_color`, `url_font`, `url_size`, `url_color`, `bg_color`, `credit_font`, `credit_text_border_color`, `no_of_text_ads`, `credit_text_alignment`, `credit_text_positioning`, `allow_publishers`, `border_type`, `status`, `ad_font_weight`, `ad_title_decoration`, `ad_desc_font_weight`, `ad_desc_decoration`, `ad_disp_url_font_weight`, `ad_disp_url_decoration`, `credit_text_font_weight`, `credit_text_decoration`, `ad_block_name`, `credit_text`, `allow_title_color`, `allow_desc_color`, `allow_disp_url_color`, `allow_bg_color`, `allow_credit_color`, `allow_bordor_type` ,  \t`line_height` ) VALUES\r\n\t\t({$exisitng_banner_row['0']}, {$exisitng_banner_row['1']}, {$exisitng_banner_row['2']}, 2 , {$exisitng_banner_row['3']}, 1, 'Arial, Helvetica, sans-serif', 12, '#000099', 'Arial, Helvetica, sans-serif', 12, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 1, 1, 0, 1, 1, 1, 2, 2, 1, 1, 1, 1, 2, 1, '{$exisitng_banner_row['1']} x {$exisitng_banner_row['2']} - Banner Only', '{$credittextid}', 1, 1, 1, 1, 1, 1,15);" );
                echo __Func010__( );
            }
        }
    }
}
*/

/*
$catalog_size_flag = 0;

if ( $catalog_size_flag == 0 )
{
    mysql_query( "ALTER TABLE ppc_ad_block add\r\n\t(`catalog_size` int(11) NOT NULL default '0',\r\n\t  `no_of_catalog_ads` int(11) NOT NULL default '0',\r\n\t  `catalog_alignment` int(11) NOT NULL default '0',\r\n\t  `catalog_line_seperator` int(11) NOT NULL default '0');" );
 //   echo __Func010__( );
}
$credit_border_color = $mysql->echo_one( "select id from ppc_credittext_bordercolor limit 0,1" );
$inline_cat = $mysql->echo_one( "select count(*) from ppc_ad_block where ad_type='7'" );
$cat_size = $mysql->echo_one( "select id from catalog_dimension where wapstatus=0 limit 0,1" );
if ( $inline_cat == 0 )
{
    mysql_query( "INSERT INTO `ppc_ad_block` (`id`, `width`, `height`, `ad_type`, `max_size`, `orientaion`, `title_font`, `title_size`, `title_color`, `desc_font`, `desc_size`, `desc_color`, `url_font`, `url_size`, `url_color`, `bg_color`, `credit_font`, `credit_text_border_color`, `no_of_text_ads`, `credit_text_alignment`, `credit_text_positioning`, `allow_publishers`, `border_type`, `status`, `ad_font_weight`, `ad_title_decoration`, `ad_desc_font_weight`, `ad_desc_decoration`, `ad_disp_url_font_weight`, `ad_disp_url_decoration`, `credit_text_font_weight`, `credit_text_decoration`, `ad_block_name`, `credit_text`, `allow_title_color`, `allow_desc_color`, `allow_disp_url_color`, `allow_bg_color`, `allow_credit_color`, `allow_bordor_type`, `line_height`,  \ttext_ad_type,catalog_size,no_of_catalog_ads,  \tcatalog_alignment,catalog_line_seperator ) VALUES\r\n\r\n('0', 300, 100, 7, 100, 1, 'Arial, Helvetica, sans-serif', 14, '#000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 14, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', {$credit_border_color}, 1, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Inline Catalog Ad Block', '{$credittextid}', 1, 1, 1, 1, 1, 1,15,1,{$cat_size},1,0,0);" );
}
$inline_text = $mysql->echo_one( "select count(*) from ppc_ad_block where ad_type='6'" );
if ( $inline_text == 0 )
{
    mysql_query( "INSERT INTO `ppc_ad_block` (`id`, `width`, `height`, `ad_type`, `max_size`, `orientaion`, `title_font`, `title_size`, `title_color`, `desc_font`, `desc_size`, `desc_color`, `url_font`, `url_size`, `url_color`, `bg_color`, `credit_font`, `credit_text_border_color`, `no_of_text_ads`, `credit_text_alignment`, `credit_text_positioning`, `allow_publishers`, `border_type`, `status`, `ad_font_weight`, `ad_title_decoration`, `ad_desc_font_weight`, `ad_desc_decoration`, `ad_disp_url_font_weight`, `ad_disp_url_decoration`, `credit_text_font_weight`, `credit_text_decoration`, `ad_block_name`, `credit_text`, `allow_title_color`, `allow_desc_color`, `allow_disp_url_color`, `allow_bg_color`, `allow_credit_color`, `allow_bordor_type`, `line_height` ) VALUES\r\n\r\n('0', 300, 100, 6, 100, 1, 'Arial, Helvetica, sans-serif', 14, '#000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 14, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', {$credit_border_color}, 1, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Inline Text Ad Block', '{$credittextid}', 1, 1, 1, 1, 1, 1,15);" );
}
$catalog_count = $mysql->echo_one( "select count(*) from ppc_ad_block where ad_type='4'" );
if ( $catalog_count == 0 )
{
    mysql_query( "\r\n INSERT INTO `ppc_ad_block` (`id`, `width`, `height`, `ad_type`, `max_size`, `orientaion`, `title_font`, `title_size`, `title_color`, `desc_font`, `desc_size`, `desc_color`, `url_font`, `url_size`, `url_color`, `bg_color`, `credit_font`, `credit_text_border_color`, `no_of_text_ads`, `credit_text_alignment`, `credit_text_positioning`, `allow_publishers`, `border_type`, `status`, `ad_font_weight`, `ad_title_decoration`, `ad_desc_font_weight`, `ad_desc_decoration`, `ad_disp_url_font_weight`, `ad_disp_url_decoration`, `credit_text_font_weight`, `credit_text_decoration`, `ad_block_name`, `credit_text`, `allow_title_color`, `allow_desc_color`, `allow_disp_url_color`, `allow_bg_color`, `allow_credit_color`, `allow_bordor_type`, `line_height`, `text_ad_type`, `catalog_size`, `no_of_catalog_ads`, `catalog_alignment`, `catalog_line_seperator`) VALUES\r\n('0', 728, 90, 4, 100, 2, 'Arial, Helvetica, sans-serif', 14, ' #000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 14, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', {$credit_border_color}, 1, 0, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'Catalog horizontal', {$credittextid}, 1, 1, 1, 1, 1, 1, 15, 1, 1, 2, 1, 0),\r\n('0', 135, 320, 4, 100, 1, 'Arial, Helvetica, sans-serif', 14, ' #000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 14, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', {$credit_border_color}, 1, 0, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'Catalog Vertical', {$credittextid}, 1, 1, 1, 1, 1, 1, 15, 1, 1, 2, 0, 0);\r\n" );
    echo __Func010__( );
}
mysql_query( "CREATE TABLE IF NOT EXISTS `wap_ad_block` (\r\n      `id` int(11) NOT NULL auto_increment,\r\n      `width` int(11) NOT NULL,\r\n      `height` int(11) NOT NULL,\r\n      `ad_type` int(11) NOT NULL,\r\n      `max_size` float(11) NOT NULL,\r\n      `orientaion` int(11) NOT NULL,\r\n      `title_font` varchar(255) NOT NULL,\r\n      `title_size` float NOT NULL,\r\n      `title_color` varchar(225) NOT NULL,\r\n      `desc_font` varchar(255) NOT NULL,\r\n      `desc_size` float NOT NULL,\r\n      `desc_color` varchar(225) NOT NULL,\r\n      `url_font` varchar(255) NOT NULL,\r\n      `url_size` float NOT NULL,\r\n      `url_color` varchar(255) NOT NULL,\r\n      `bg_color` varchar(255) NOT NULL,\r\n      `credit_font` varchar(255) NOT NULL,\r\n      `credit_text_border_color` varchar(255) NOT NULL,\r\n      `no_of_text_ads` int(11) NOT NULL,\r\n      `credit_text_alignment` int(11) NOT NULL,\r\n      `credit_text_positioning` int(11) NOT NULL,\r\n      `allow_publishers` int(11) NOT NULL,\r\n      `border_type` int(11) NOT NULL,\r\n      `status` int(11) NOT NULL default '0',\r\n      `ad_font_weight` int(11) NOT NULL,\r\n      `ad_title_decoration` int(11) NOT NULL,\r\n      `ad_desc_font_weight` int(11) NOT NULL,\r\n      `ad_desc_decoration` int(11) NOT NULL,\r\n      `ad_disp_url_font_weight` int(11) NOT NULL,\r\n      `ad_disp_url_decoration` int(11) NOT NULL,\r\n      `credit_text_font_weight` int(11) NOT NULL,\r\n      `credit_text_decoration` int(11) NOT NULL,\r\n      `ad_block_name` varchar(50) NOT NULL,\r\n      `credit_text` varchar(50) NOT NULL,\r\n      `allow_title_color` int(11) NOT NULL,\r\n      `allow_desc_color` int(11) NOT NULL,\r\n      `allow_disp_url_color` int(11) NOT NULL,\r\n      `allow_bg_color` int(11) NOT NULL,\r\n      `allow_credit_color` int(11) NOT NULL,\r\n      `allow_bordor_type` int(11) NOT NULL,\r\n      `line_height` int(11) NOT NULL default '15',\r\n      `text_ad_type` int(11) NOT NULL default '1',\r\n      `catalog_size` int(11) NOT NULL default '0',\r\n\t  `no_of_catalog_ads` int(11) NOT NULL default '0',\r\n\t  `catalog_alignment` int(11) NOT NULL default '0',\r\n\t  `catalog_line_seperator` int(11) NOT NULL default '0',\r\n      \r\n      PRIMARY KEY  (`id`)\r\n    ) ENGINE=InnoDB utf8_unicode_ci   AUTO_INCREMENT=1 ;" );
//echo __Func010__( );
if ( $mysql->total( "wap_ad_block", "" ) == 0 )
{
    $credit_text = $mysql->echo_one( "select id from ppc_publisher_credits limit 0,1" );
    $catalog = $mysql->echo_one( "select id from catalog_dimension where wapstatus=1  limit 0,1" );
    mysql_query( "\r\nINSERT INTO `wap_ad_block` (`id`, `width`, `height`, `ad_type`, `max_size`, `orientaion`, `title_font`, `title_size`, `title_color`, `desc_font`, `desc_size`, `desc_color`, `url_font`, `url_size`, `url_color`, `bg_color`, `credit_font`, `credit_text_border_color`, `no_of_text_ads`, `credit_text_alignment`, `credit_text_positioning`, `allow_publishers`, `border_type`, `status`, `ad_font_weight`, `ad_title_decoration`, `ad_desc_font_weight`, `ad_desc_decoration`, `ad_disp_url_font_weight`, `ad_disp_url_decoration`, `credit_text_font_weight`, `credit_text_decoration`, `ad_block_name`, `credit_text`, `allow_title_color`, `allow_desc_color`, `allow_disp_url_color`, `allow_bg_color`, `allow_credit_color`, `allow_bordor_type`, `line_height`, `text_ad_type`, `catalog_size`, `no_of_catalog_ads`, `catalog_alignment`, `catalog_line_seperator`) VALUES\r\n('0', 300, 50, 3, 10, 1, 'Arial, Helvetica, sans-serif', 12, ' #000099', 'Arial, Helvetica, sans-serif', 12, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', {$credit_border_color}, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '6:1 Format-1', {$credit_text}, 1, 1, 1, 1, 1, 1, 12, 3, 0, 1, 0, 0),\r\n('0', 216, 36, 3, 10, 1, 'Arial, Helvetica, sans-serif', 10, ' #000099', 'Arial, Helvetica, sans-serif', 10, '#0F0F0F', 'Arial, Helvetica, sans-serif', 8, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', {$credit_border_color}, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '6:1 Format-2', {$credit_text}, 1, 1, 1, 1, 1, 1, 10, 2, 0, 1, 0, 0),\r\n('0', 168, 28, 3, 10, 1, 'Arial, Helvetica, sans-serif', 10, ' #000099', 'Arial, Helvetica, sans-serif', 8, '#0F0F0F', 'Arial, Helvetica, sans-serif', 8, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', {$credit_border_color}, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '6:1 Format-3', {$credit_text}, 1, 1, 1, 1, 1, 1, 10, 2, 0, 1, 0, 0),\r\n('0', 300, 75, 3, 10, 1, 'Arial, Helvetica, sans-serif', 14, ' #000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 14, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', {$credit_border_color}, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '4:1 Format-1', {$credit_text}, 1, 1, 1, 1, 1, 1, 15, 1, 0, 1, 0, 0),\r\n('0', 216, 54, 3, 10, 1, 'Arial, Helvetica, sans-serif', 12, ' #000099', 'Arial, Helvetica, sans-serif', 12, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', {$credit_border_color}, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '4:1 Format-2', {$credit_text}, 1, 1, 1, 1, 1, 1, 12, 1, 0, 1, 0, 0),\r\n('0', 168, 42, 3, 10, 1, 'Arial, Helvetica, sans-serif', 10, ' #000099', 'Arial, Helvetica, sans-serif', 10, '#0F0F0F', 'Arial, Helvetica, sans-serif', 10, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', {$credit_border_color}, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '4:1 Format-3', {$credit_text}, 1, 1, 1, 1, 1, 1, 10, 3, 0, 1, 0, 0),\r\n('0', 210, 85, 4, 10, 2, 'Arial, Helvetica, sans-serif', 14, ' #000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 14, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '{$credit_border_color}', 1, 0, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'Wap catalog', '{$credit_text}', 1, 1, 1, 1, 1, 1, 15, 1, {$catalog}, 1, 1, 0);\r\n" );
 //   echo __Func010__( );
}





*/





//include( "trigger.php" );













































$row = mysql_query( "select a.id from ppc_ads a left join ad_location_mapping b on a.id=b.adid where b.country is null " );
while ( 0 < count( $row ) && ( $rowdata = mysql_fetch_row( $row ) ) )
{
    echo '->'.$rowdata['0'];
	//mysql_query( "INSERT INTO `ad_location_mapping` (`adid` , `country` , `region` , `city`) VALUES ('{$rowdata['0']}', '00', '00', '00')" );
}




/*



if ( $mysql->total( "banner_dimension", "" ) == 0 )
{
    mysql_query( "BEGIN" );
    $err = 0;
    $condition12 = "ad_type=3 or ad_type=2";
    $banner_dime = mysql_query( "select height,width,max_size,id from ppc_ad_block where {$condition12}" );
    if ( __Func010__( ) )
    {
        echo __Func010__( );
        $err = 1;
    }
    while ( $banner_dime1 = __Func011__( $banner_dime ) )
    {
        $repeatno = $mysql->echo_one( "select id from banner_dimension where width='{$banner_dime1['1']}' and height='{$banner_dime1['0']}' and wap_status='0'" );
        if ( __Func010__( ) )
        {
            echo __Func010__( );
            $err = 1;
        }
        if ( $repeatno == "" )
        {
            mysql_query( "insert into banner_dimension values('0',{$banner_dime1['1']},{$banner_dime1['0']},{$banner_dime1['2']},'0')" );
            if ( __Func010__( ) )
            {
                echo __Func010__( );
                $err = 1;
            }
            $lastid = __Func012__( );
            if ( __Func010__( ) )
            {
                echo __Func010__( );
                $err = 1;
            }
            mysql_query( "update ppc_ad_block set max_size='{$lastid}' where id='{$banner_dime1['3']}'" );
            if ( __Func010__( ) )
            {
                echo __Func010__( );
                $err = 1;
            }
        }
        else
        {
            mysql_query( "update ppc_ad_block set max_size='{$repeatno}' where id='{$banner_dime1['3']}'" );
            if ( __Func010__( ) )
            {
                echo __Func010__( );
                $err = 1;
            }
        }
    }
    $wapsize5 = mysql_query( "select height,width,max_size,id from wap_ad_block where {$condition12}" );
    if ( __Func010__( ) )
    {
        echo __Func010__( );
        $err = 1;
    }
    while ( $wapbann51 = __Func011__( $wapsize5 ) )
    {
        $repeatwapno = $mysql->echo_one( "select id from banner_dimension where width='{$wapbann51['1']}' and height='{$wapbann51['0']}' and wap_status='1'" );
        if ( $repeatwapno == "" )
        {
            mysql_query( "insert into banner_dimension values('0',{$wapbann51['1']},{$wapbann51['0']},{$wapbann51['2']},'1')" );
            if ( __Func010__( ) )
            {
                echo __Func010__( );
                $err = 1;
            }
            $lastid1 = __Func012__( );
            if ( __Func010__( ) )
            {
                echo __Func010__( );
                $err = 1;
            }
            mysql_query( "update wap_ad_block set max_size='{$lastid1}' where id='{$wapbann51['3']}'" );
            if ( __Func010__( ) )
            {
                echo __Func010__( );
                $err = 1;
            }
        }
        else
        {
            mysql_query( "update wap_ad_block set max_size='{$repeatwapno}' where id='{$wapbann51['3']}'" );
            if ( __Func010__( ) )
            {
                echo __Func010__( );
                $err = 1;
            }
        }
    }
    $ban1 = mysql_query( "select distinct(bannersize) from ppc_ads where bannersize is not null and adtype='1' and wapstatus='0'" );
    if ( __Func010__( ) )
    {
        echo __Func010__( );
        $err = 1;
    }
    while ( $bann501 = __Func011__( $ban1 ) )
    {
        $filesize = mysql_query( "select max_size from ppc_ad_block where id='{$bann501['0']}'" );
        if ( __Func010__( ) )
        {
            echo __Func010__( );
            $err = 1;
        }
        $filesize1 = __Func011__( $filesize );
        mysql_query( "update ppc_ads set bannersize=".$filesize1[0]." where bannersize=".$bann501[0]." and adtype='1' and wapstatus='0'" );
        if ( __Func010__( ) )
        {
            echo __Func010__( );
            $err = 1;
        }
    }
    $wapban1 = mysql_query( "select distinct(bannersize) from ppc_ads where bannersize is not null and adtype='1' and wapstatus='1'" );
    if ( __Func010__( ) )
    {
        echo __Func010__( );
        $err = 1;
    }
    while ( $wapbann501 = __Func011__( $wapban1 ) )
    {
        $fsize = $mysql->echo_one( "select max_size from wap_ad_block where id='{$wapbann501['0']}'" );
        if ( __Func010__( ) )
        {
            echo __Func010__( );
            $err = 1;
        }
        mysql_query( "update ppc_ads set bannersize=".$fsize." where bannersize=".$wapbann501[0]." and adtype='1' and wapstatus='1'" );
        if ( __Func010__( ) )
        {
            echo __Func010__( );
            $err = 1;
        }
    }
    $pub_ser_ban1 = mysql_query( "select distinct(bannersize) from ppc_public_service_ads where bannersize is not null and adtype='1' and wapstatus='0'" );
    if ( __Func010__( ) )
    {
        echo __Func010__( );
        $err = 1;
    }
    while ( $pub_ser_ads = __Func011__( $pub_ser_ban1 ) )
    {
        $filesize = mysql_query( "select max_size from ppc_ad_block where id='{$pub_ser_ads['0']}'" );
        if ( __Func010__( ) )
        {
            echo __Func010__( );
            $err = 1;
        }
        $filesize1 = __Func011__( $filesize );
        mysql_query( "update ppc_public_service_ads set bannersize=".$filesize1[0]." where bannersize=".$pub_ser_ads[0]." and adtype='1' and wapstatus='0'" );
        if ( __Func010__( ) )
        {
            echo __Func010__( );
            $err = 1;
        }
    }
    $wappublicban1 = mysql_query( "select distinct(bannersize) from ppc_public_service_ads where bannersize is not null and adtype='1' and wapstatus='1'" );
    if ( __Func010__( ) )
    {
        echo __Func010__( );
        $err = 1;
    }
    while ( $wappublicban = __Func011__( $wappublicban1 ) )
    {
        $fsize = $mysql->echo_one( "select max_size from wap_ad_block where id='{$wappublicban['0']}'" );
        if ( __Func010__( ) )
        {
            echo __Func010__( );
            $err = 1;
        }
        mysql_query( "update ppc_public_service_ads set bannersize=".$fsize." where bannersize=".$wappublicban[0]." and adtype='1' and wapstatus='1'" );
        if ( __Func010__( ) )
        {
            echo __Func010__( );
            $err = 1;
        }
    }
    if ( $err == 1 )
    {
        mysql_query( "ROLLBACK" );
    }
    else
    {
        mysql_query( "COMMIT" );
    }
}
*/




/*
if ( $mysql->echo_one( "select id from system_keywords where  1 limit 0,1" ) == "" )
{
    include( "build-keywords.php" );
}
*/