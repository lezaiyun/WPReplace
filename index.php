<?php
/**
Plugin Name: WPReplace内容字符替换插件
Plugin URI: https://www.laobuluo.com/2693.html
Description: WordPress实现可视化替换文章内容、标题，评论昵称和评论内容字符。站长互助QQ群： <a href="https://jq.qq.com/?_wv=1027&k=5gBE7Pt" target="_blank"> <font color="red">594467847</font></a>
Version: 1.3.1
Author: 老部落（By:老赵）
Author URI: https://www.laobuluo.com
*/

define('WPReplace_INDEXFILE', 'wpreplace/index.php');

add_action('admin_menu', 'wprelace_add_setting_page');


function wprelace_add_setting_page() {
	Add_management_page('WPRelace设置', 'WPRelace设置', 'manage_options', __FILE__, 'wprelace_setting_page');
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
            case 4:
                # 评论用户邮箱和网址替换
                $result1 = $wpdb->query(
                    "UPDATE {$wpdb->prefix}comments SET `comment_author_email` = REPLACE( `comment_author_email`, '{$originalContent}', '{$newContent}');"
                );
                $result2 = $wpdb->query(
                    "UPDATE {$wpdb->prefix}comments SET `comment_author_url` = REPLACE( `comment_author_url`, '{$originalContent}', '{$newContent}');"
                );
                break;
            case 5:
                # 文章摘要内容替换
                $result = $wpdb->query(
                    "UPDATE {$wpdb->prefix}posts SET `post_excerpt` = REPLACE( `post_excerpt`, '{$originalContent}', '{$newContent}');"
                );
                break;
		}
		?>
       <div class="notice notice-success settings-error is-dismissible"><p><strong>替换完成。</strong></p></div>

		<?php
	}
	?>

      <div class="wrap">
        <h1 class="wp-heading-inline">WPReplace - WordPress内容字符替换插件</h1><a href="https://www.laobuluo.com/2693.html" target="_blank"class="page-title-action">插件介绍</a>
    <hr class="wp-header-end">
        
            <p>WPReplace插件，我们可以快速后台可视化替换WordPress文章内容、标题，评论昵称、内容字符，快速帮助迁移WordPress网站内容静态文件或者是字符替换功能。</p>
        <p>快速导航： <a href="https://www.laobuluo.com/2113.html" target="_blank"><font color="red">新人建站常用的虚拟主机/云服务器</font></a> / 站长QQ群： <a href="https://jq.qq.com/?_wv=1027&k=5gBE7Pt" target="_blank"> <font color="red">594467847</font></a>（交流建站和运营） / 公众号：QQ69377078（插件反馈）</p>
      
      <hr/>
      
        <form action="<?php echo wp_nonce_url('./admin.php?page=' . WPReplace_INDEXFILE); ?>" name="wpreplaceform" method="post">
         <table class="form-table">
                <tr>
                    <th scope="row">
                    目标内容
                </th>
                    <td>
                        <input type="text" name="originalContent" value="" size="50"
                               placeholder="输入你需要替换的目标内容"/>

                        <p>我们希望哪些字符、内容被替换？</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                   替换内容
                </th>
                    <td>
                        <input type="text" name="newContent" value="" size="50"
                               placeholder="输入你需要替换后内容"/>
                        <p>我们希望将目标替换成什么内容？</p>
                    </td>
                </tr>
                <tr>
                     <th scope="row">
                   替换项目选择器
                </th>
                    <td>
                        <select name="replaceSelector" required style="width:180px;">
                            <option value="1">文章内容文字/字符替换</option>
                            <option value="2">文章标题/字符替换</option>
                            <option value="3">评论用户昵称/内容字符替换</option>
                            <option value="4">评论用户邮箱/网址替换</option>
                            <option value="5">文章摘要批量替换</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th>

                    </th>
                    <td><input type="submit" name="submit" value="执行替换" class="button button-primary" /></td>
                </tr>
            </table>
            <hr>
        <p><strong>注意事项</strong></p>
        <p>1. 不熟悉的用户建议备份数据库，确保错误后可以恢复</p>
        <p>2. 根据需要替换对象在选择器选择对象</p>
        

        </form>

    </div>

    <?php
}
?>
