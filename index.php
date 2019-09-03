<?php
/**
Plugin Name: WPReplace
Plugin URI: https://www.laobuluo.com/2693.html
Description: WordPress实现可视化替换文章内容、标题，评论昵称和评论内容字符。站长互助QQ群： <a href="https://jq.qq.com/?_wv=1027&k=5gBE7Pt" target="_blank"> <font color="red">594467847</font></a>
Version: 1.0
Author: 老部落（By:老赵）
Author URI: https://www.laobuluo.com
*/

define('WPReplace_INDEXFILE', 'WPReplace/index.php');

add_action('admin_menu', 'wprelace_add_setting_page');


function wprelace_add_setting_page() {
	add_menu_page('WPRelace设置', 'WPRelace设置', 'manage_options', __FILE__, 'wprelace_setting_page');
}


function wprelace_setting_page() {
	if (!current_user_can('manage_options')) {
		wp_die('Insufficient privileges!');
	}
	if (isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce']) && !empty($_POST)) {
		global $wpdb;

		$originalContent = sanitize_text_field(stripslashes($_POST['originalContent']));
		$newContent = sanitize_text_field(stripslashes($_POST['newContent']));
		$replaceSelector = sanitize_text_field(stripslashes($_POST['replaceSelector']));

		switch (intval($replaceSelector)) {
			case 1:
				# 文章内容文字/字符替换
				$result = $wpdb->query(
					"UPDATE {$wpdb->prefix}posts SET `post_content` = REPLACE( `post_content`, '{$originalContent}', '{$newContent}');"
				);
				break;
			case 2:
				# 文章标题/字符替换
				$result = $wpdb->query(
					"UPDATE {$wpdb->prefix}posts SET `post_title` = REPLACE( `post_title`, '{$originalContent}', '{$newContent}');"
				);
				break;
			case 3:
				# 评论用户昵称/内容字符替换
				$result1 = $wpdb->query(
					"UPDATE {$wpdb->prefix}comments SET `comment_author` = REPLACE( `comment_author`, '{$originalContent}', '{$newContent}');"
				);
				$result2 = $wpdb->query(
					"UPDATE {$wpdb->prefix}comments SET `comment_content` = REPLACE( `comment_content`, '{$originalContent}', '{$newContent}');"
				);
				break;
		}
		?>
        <div class="updated"><p>替换完成!!!</p></div>

		<?php
	}
	?>

    <style>
        table {
            border-collapse: collapse;
        }

        table, td, th {border: 1px solid #cccccc;padding:5px;}
        .buttoncss {background-color: #4CAF50;
            border: none;cursor:pointer;
            color: white;
            padding: 15px 22px;
            text-align: center;
            text-decoration: none;
            display: inline-block;border-radius: 5px;
            font-size: 12px;font-weight: bold;
        }
        .buttoncss:hover {
            background-color: #008CBA;
            color: white;
        }
        input,select{border: 1px solid #ccc; padding: 5px 0px; border-radius: 3px; padding-left:5px;}
    </style>

    <div style="margin:5px;">
        <h2>WPReplace - WordPress内容字符替换插件</h2>
        <hr/>
            <p>WPReplace插件，我们可以快速后台可视化替换WordPress文章内容、标题，评论昵称、内容字符，快速帮助迁移WordPress网站内容静态文件或者是字符替换功能。</p>
        <p>插件网站： <a href="https://www.laobuluo.com" target="_blank">老部落</a> / <a href="https://www.laobuluo.com/2693.html" target="_blank">WPReplace插件发布页面地址</a>  / 站长创业交流QQ群： <a href="https://jq.qq.com/?_wv=1027&k=5gBE7Pt" target="_blank"> <font color="red">594467847</font></a>（宗旨：多做事，少说话）</p>
        <p>推荐文章： <a href="https://www.laobuluo.com/2113.html" target="_blank">新人建站常用的虚拟主机/云服务器 常用主机商选择建议</a></p>   
      <hr/>
      <p><font color="red"> <b>！！！！ 强烈提醒/建议：使用替换数据库插件之前请先自行【备份网站数据库】 ！！！！</b></font></p>
        <form action="<?php echo wp_nonce_url('./admin.php?page=' . WPReplace_INDEXFILE); ?>" name="wpreplaceform" method="post">
            <table>
                <tr>
                    <td style="text-align:right;">
                        <b>目标内容：</b>
                    </td>
                    <td>
                        <input type="text" name="originalContent" value="" size="50"
                               placeholder="输入你需要替换的目标内容"/>

                        <p>我们希望哪些字符、内容被替换？</p>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;">
                        <b>替换内容：</b>
                    </td>
                    <td>
                        <input type="text" name="newContent" value="" size="50"
                               placeholder="输入你需要替换后内容"/>
                        <p>我们希望将目标替换成什么内容？</p>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;">
                        <b>替换项目选择器：</b>
                    </td>
                    <td>
                        <select name="replaceSelector" required style="width:180px;">
                            <option value="1">文章内容文字/字符替换</option>
                            <option value="2">文章标题/字符替换</option>
                            <option value="3">评论用户昵称/内容字符替换</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th>

                    </th>
                    <td><input type="submit" name="submit" value="执行替换字符操作" class="buttoncss" /></td>
                </tr>
            </table>

        </form>

    </div>

    <?php
}
?>
