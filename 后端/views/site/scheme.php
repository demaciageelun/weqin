<?php
/**
 * Created by PhpStorm
 * User: 风哀伤
 * Date: 2021/2/22
 * Time: 9:56 上午
 * @copyright: ©2020 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */
?>
<div id="app">

</div>
<script>
    const app = new Vue({
        el: '#app',
        created() {
            let url_scheme = '<?=$url ?>';
            console.log(url_scheme)
            if (url_scheme) {
                location.href = decodeURIComponent(url_scheme);
            }
            console.log(22)
        }
    });
</script>
