<?php
echo '<div style="width:90%;margin:auto auto;">

<table width="100%" class="grid">
<caption>常见问题及使用技巧</caption>
<tr>
<td>
<ul class="ulnav">
<li>&bull; <a href="#tip1">常用模板文件说明</a></li>
<li>&bull; <a href="#tip2">常用配置文件说明</a></li>
<li>&bull; <a href="#tip3">新系统入门必读</a></li>
<li>&bull; <a href="#tip4">整合其它账号登陆接口</a></li>
<li>&bull; <a href="#tip5">整合Ucenter接口</a></li>
</ul>
</td>
<td>
<ul class="ulnav">
<li>&bull; <a href="#tip6">制作自定义页面</a></li>
<li>&bull; <a href="#tip7">远程调用区块</a></li>
<li>&bull; <a href="#tip8">增加和管理广告</a></li>
<li>&bull; <a href="#tip9">点广告增加积分配置方法</a></li>
<li>&bull; <a href="#tip10">修改小说类别</a></li>
</ul>
</td>
</tr>
</table>

<div class="block">
<div class="blocktitle"><a name="tip1">常用模板文件说明</a></div>
<div class="blockcontent">
&nbsp;&nbsp;&nbsp;&nbsp;1、系统有一个总体风格模板theme.html，定义了统一的页头页尾及区块布局。默认在 /themes/****目录下，**** 是指您在后台系统定义里面的风格名称，比如 book，那就使用 themes/book/theme.html这个模板。模板为html格式， {&#63; 和 &#63;}之间内容为模板中的变量或者操作语句。<br />
&nbsp;&nbsp;&nbsp;&nbsp;2、其他模板目录安排如下：<br />
系统功能模板在 /templates 目录下，模块功能模板在 /modules/模块目录/templates下（如小说系统是 /modules/article/templates，论坛是 /modules/forum/templates）。各自模块的区块模板在blocks的子目录下，比如小区块的模板在 /modules/article/templates/blocks<br />
&nbsp;&nbsp;&nbsp;&nbsp;3、其他常用模板说明：<br />
首页(/index.php) - /themes/风格名称/theme.html (定制首页可使用其他模板)<br />
小说信息页(/modules/article/articleinfo.php) - /modules/article/templates/articleinfo.html<br />
小说分类列表(/modules/article/index.php) - /modules/article/templates/articlelist.html<br />
小说排行榜(/modules/article/toplist.php) - /modules/article/templates/toplist.html<br />
阅读页面目录页 - /modules/article/templates/index.html
阅读页面章节页 - /modules/article/templates/style.html
全文阅读页 - /modules/article/templates/fulltext.html
</div>
</div>

<div class="block">
<div class="blocktitle"><a name="tip2">常用配置文件说明</a></div>
<div class="blockcontent">
&nbsp;&nbsp;&nbsp;&nbsp;1、系统配置 - 保存于 /configs/define.php
，里面的参数跟后台系统定义相对应。如果因为系统配置错误无法进入后台，则需要手工修改本文件。本文件里面几个主要参数如下：<br />
JIEQI_URL - 网站根路径，可以不填，也可以设置成网站路径，后面不带"/"，如 http://www.jieqi.com<br />
JIEQI_DB_TYPE - 数据库类型，目前必须是 mysql<br />
JIEQI_DB_CHARSET - 数据库编码，根据实际数据库编码设置，如 gbk<br />
JIEQI_DB_PREFIX - 数据表前缀，目前必须是 jieqi<br />
JIEQI_DB_HOST - 数据库服务器名字，默认 localhost<br />
JIEQI_DB_USER - 数据库用户名<br />
JIEQI_DB_PASS - 数据库用户密码<br />
JIEQI_DB_NAME - 数据库名称<br />
JIEQI_IS_OPEN - 网站是否开放，1 表示开放，0 表示关闭<br />
&nbsp;&nbsp;&nbsp;&nbsp;2、后台导航栏配置 - 系统功能导航的配置文件为 /configs/adminmenu.php ，每个模块的后台导航配置文件为 /configs/模块目录名/adminmenu.php<br />
&nbsp;&nbsp;&nbsp;&nbsp;3、小说分类修改 - 请手工编辑配置文件<br />
/configs/article/sort.php<br />
</div>
</div>

<div class="block">
<div class="blocktitle"><a name="tip3">新系统入门必读</a></div>
<div class="blockcontent">
&nbsp;&nbsp;&nbsp;&nbsp;系统装好后，在具体发数据前，用户需要做以下几件事情：<br />
&nbsp;&nbsp;&nbsp;&nbsp;1、进入后台系统定义里面，把每个系统参数检查和设置下，并保存。<br />
&nbsp;&nbsp;&nbsp;&nbsp;2、后台系统和每个模块的参数设置检查并保存。<br />
&nbsp;&nbsp;&nbsp;&nbsp;3、进入后台用户组管理，按照您的实际需要将用户分成几个用户组。<br />
&nbsp;&nbsp;&nbsp;&nbsp;4、用户组确认后，设置系统和每个模块的权限管理。<br />
&nbsp;&nbsp;&nbsp;&nbsp;5、进入后台的头衔管理，按照您的实际需要设置头衔。<br />
&nbsp;&nbsp;&nbsp;&nbsp;6、头衔设置后之后，设置系统个每个模块的权利设置。<br />
&nbsp;&nbsp;&nbsp;&nbsp;7、用小说连载系统的，请预先设置好小说分类。<br />
&nbsp;&nbsp;&nbsp;&nbsp;8、根据实际需要制作本站模板。<br />
</div>
</div>

<div class="block">
<div class="blocktitle"><a name="tip4">整合其它账号登陆接口</a></div>
<div class="blockcontent">
杰奇1.8以上的版本，支持QQ、新浪微博及淘宝账号的一键登录，需要使用这些功能，要分别向对应网站申请登录接口的服务。<br />
&nbsp;&nbsp;&nbsp;&nbsp;申请好之后可以获得一个接口ID和通讯密钥，然后修改本站/api/接口名/config.ini.php设置好以上信息即可使用。
</div>
</div>

<div class="block">
<div class="blocktitle"><a name="tip5">整合Ucenter接口</a></div>
<div class="blockcontent">
&nbsp;&nbsp;&nbsp;&nbsp;1、安装好Ucenter服务端程序，一般用Discuz的话默有包含。<br />
&nbsp;&nbsp;&nbsp;&nbsp;2、杰奇程序中原来的 /include/funuser.php 做好备份，比如改名成 funuser_default.php<br />
然后把该目录下 funuser_ucenter.php 改名成 funuser.php<br />
&nbsp;&nbsp;&nbsp;&nbsp;3、编辑JIEQI CMS中的 /api/ucenter/config.inc.php<br />
   如果使用数据连接，设置好相关参数<br />
   通信密钥可以自己随意设定，跟Ucenter后台设置要相同<br />
   UCenter 数据库表前缀，包含数据库名及数据表前缀，必须和实际使用对应<br />
   UCenter 的 URL 地址必须设置正确<br />
   <span class="hot">注意:本目录下的 data 子目录要可写，是接口的缓存数据目录。</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;4、进入Ucenter后台，“应用管理”点“添加新应用”<br />
   选择安装方式: 自定义安装<br />
   应用名称：填网站名，比如 JIEQI CMS<br />
   应用的 URL：填JIEQI CMS网址，如 http://www.jieqi.com<br />
   应用 IP: 一般可以留空，或设置成JIEQI CMS服务器ip<br />
   通信密钥: 自己设置一个密码<br />
   应用类型: 选其他<br />
   应用的物理路径: 留空<br />
   查看个人资料页面地址: /userinfo.php?id=%s<br />
   应用接口文件名称: uc.php<br />
   标签单条显示模板: 留空<br />
   标签模板标记说明: 留空<br />
   是否开启同步登录: 是<br />
   是否接受通知: 是<br /><br />
   然后提交<br />
   这时候再点应用管理，如果提示通讯成功，就设置好了<br />
</div>
</div>

<div class="block">
<div class="blocktitle"><a name="tip6">制作自定义页面</a></div>
<div class="blockcontent">
自定义页面是指新建一个php程序，然后调用对应的模块和模板，来实现独立页面显示，一般用于网站首页或者栏目首页。<br />可以参考文件/custom.php的写法，主要步骤如下：<br />
&nbsp;&nbsp;&nbsp;&nbsp;1、制作本页调用区块的php配置文件，如 /configs/custom.php，这个配置文件里面调用区块的写法可参考后台区块管理米面每个区块的调用参数。<br />
&nbsp;&nbsp;&nbsp;&nbsp;2、准备一个html页面作为模板文件，比如 /templates/custom.html 模板中设置好各个区块显示位置<br />
&nbsp;&nbsp;&nbsp;&nbsp;3、模仿/custom.php，设置好需要的模板和区块配置文件即可。<br /><br />
</div>
</div>

<div class="block">
<div class="blocktitle"><a name="tip7">远程调用区块</a></div>
<div class="blockcontent">
&nbsp;&nbsp;&nbsp;&nbsp;如果您需要在一台服务器上远程调用另一台服务器上的区块，可以使用js来调用。
每个区块的js调用写法，请参看区块管理里面的“远程调用js”。
</div>
</div>

<div class="block">
<div class="blocktitle"><a name="tip8">增加和管理广告</a></div>
<div class="blockcontent">
&nbsp;&nbsp;&nbsp;&nbsp;1、全站通栏广告<br />
分底部通栏和顶部通栏，代码设置在后台的系统定义里面，这个内容可以显示在全站所有动态页面。<br />
&nbsp;&nbsp;&nbsp;&nbsp;2、首页广告<br />
首页除了统一的顶部和底部，中间都是由一个个区块构成。用户可以在后台的区块管理里面调整某个区块是否显示和显示位置。同样的，如 果要增加广告，或者公告之类区块，也是在区块管理里面增加一个自定义区块，自己填入代码，选择好位置保存即可。<br />
注意：每个区块都有一个序号，序号大小表示排列顺序，不同区块之间序号不要重复。<br />
&nbsp;&nbsp;&nbsp;&nbsp;3、阅读页面广告<br />
阅读页面因为是静态的html，所以广告都通过调用js实现的。默认的阅读页面模板如下：<br />
a、目录页面模板<br />
modules/article/templates/index.html 里面的顶部和底部各默认设置了两个广告js分别是 configs/article/indextop.js 和configs/article/indexbottom.js。<br />
b、章节阅读页面模板<br />
modules/article/templates/style.html 里面的顶部和底部各默认设置了两个广告js分别是 configs/article/pagetop.js 和configs/article/pagebottom.js。<br />
c、全文阅读模板<br />
modules/article/templates/fulltext.html 里面的顶部和底部各默认设置了两个广告js分别是 configs/article/fulltop.js 和configs/article/fullbottom.js<br />
注意：以上模板用户都可以修改，模板修改后对于以前已经生成的html，可以在后台里面使用小说重新生成功能。<br />
&nbsp;&nbsp;&nbsp;&nbsp;4、其他广告<br />
如果用户需要在一些特殊页面放置广告，则需要直接找到这个页面的模板，通过修改模板实现。<br />
</div>
</div>

<div class="block">
<div class="blocktitle"><a name="tip9">点广告增加积分配置方法</a></div>
<div class="blockcontent">
&nbsp;&nbsp;&nbsp;&nbsp;首先需要设置好相关参数，在后台系统管理里面的参数设置，设置好“每次点击广告积分”和“每天最多有效广告点击次数”，并保存。然后根据不同的广告设置不同的代码。<br /><br />

系统统计广告点击积分的程序为根目录下的 /adclick.php<br />
程序有两个参数<br />
第一个 id ，必须为数字，是广告序号，不同广告不要重复（同一个广告多次点击不会重复计分）<br />
第二个 url ，是广告点击后要跳转的网址（iframe调用模式下不用设置）<br /><br />


本程序有两种调用方法，例子如下<br />

1、自己写代码的文字或者图片链接广告，例子如下<br />
&lt;a href="http://www.domain.com/adclick.php?id=123&url=http://www.ad.com" target="_blank"&gt;点击显示广告&lt;/a&gt;<br />

其中 http://www.domain.com 是本站域名， 123 是广告序号, http://www.ad.com 是跳转的网址<br /><br />


2、用iframe调用显示的广告，例子如下<br />
&lt;iframe id="jieqiad_123" name="jieqiad_123" width="760" height="60" align="center" marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="no" src="http://www.domain.com/ad.html" onfocus="javascript:adimg=new Image(); adimg.src=\'http://www.domain.com/adclick.php?id=123\';"&gt;&lt;/iframe&gt;<br />

其中 onfocus 是触发点击统计的， http://www.domain.com 是只本站域名， 123 是广告序号
</div>
</div>

<div class="block">
<div class="blocktitle"><a name="tip10">修改小说类别</a></div>
<div class="blockcontent">
&nbsp;&nbsp;&nbsp;&nbsp;把分类阅读和发表小说时的系统默认小说分类，修改成别的分类，如：把“都市言情”改名为“情感故事”：
找到配置文件 configs/article/sort.php,用记事本或别的文本编辑工具打开对其进行修改，文档中是一个php数组，在程序里面是依据此处数组的序号来判断小说类别，所以序号不要重复，也不要轻易修改。将文档中的“都市言情”改名为“情感故事”即可，分类简称“都市”也可作类似修改。如使用的是系统菜单，也要对其进行修改，下方有详细说明。 最后刷新区块（见刷新页面）可看到更改后的效果。 
</div>
</div>

<div class="block tc">&#80;&#111;&#119;&#101;&#114;&#101;&#100;&#32;&#98;&#121;&#32;&#74;&#73;&#69;&#81;&#73;&#32;&#67;&#77;&#83;&#32;&#26480;&#22855;&#32593;&#32476;&#65288;&#106;&#105;&#101;&#113;&#105;&#46;&#99;&#111;&#109;&#65289;</div>

</div>';
?>