<?php
echo '

<div class="blockc">'.$this->_tpl_vars['jieqi_pageblocks']['11']['content'].'</div>

<div class="blockc"><div class="blockcontent">'.$this->_tpl_vars['jieqi_pageblocks']['21']['content'].'</div></div>

<div class="block">
	<div class="blocktitle">'.$this->_tpl_vars['jieqi_pageblocks']['31']['title'].'</div>
	<div class="blockcontent">'.$this->_tpl_vars['jieqi_pageblocks']['31']['content'].'</div>
</div>

<div class="block">
	<div class="blocktitle">点击榜</div>
	<div class="blockcontent">
		<div class="cf mb">
		<ul class="tabb tab3">
	  	<li><a href="javascript:void(0)" onclick="selecttab(this);" class="selected">周点击</a></li>
	  	<li><a href="javascript:void(0)" onclick="selecttab(this);">月点击</a></li>
				<li><a href="javascript:void(0)" onclick="selecttab(this);">总点击</a></li>
	  </ul>
		</div>
	<div>
	  <div>'.$this->_tpl_vars['jieqi_pageblocks']['41']['content'].'</div>
	  <div style="display:none;">'.$this->_tpl_vars['jieqi_pageblocks']['42']['content'].'</div>
			<div style="display:none;">'.$this->_tpl_vars['jieqi_pageblocks']['43']['content'].'</div>
	</div>
	</div>
</div>

<div class="block">
	<div class="blocktitle">推荐榜</div>
	<div class="blockcontent">
		<div class="cf mb">
		<ul class="tabb tab3">
	  	<li><a href="javascript:void(0)" onclick="selecttab(this);" class="selected">周推荐</a></li>
	  	<li><a href="javascript:void(0)" onclick="selecttab(this);">月推荐</a></li>
				<li><a href="javascript:void(0)" onclick="selecttab(this);">总推荐</a></li>
	  </ul>
		</div>
	<div>
	  <div>'.$this->_tpl_vars['jieqi_pageblocks']['51']['content'].'</div>
	  <div style="display:none;">'.$this->_tpl_vars['jieqi_pageblocks']['52']['content'].'</div>
			<div style="display:none;">'.$this->_tpl_vars['jieqi_pageblocks']['53']['content'].'</div>
	</div>
	</div>
</div>

<div class="block">
	<div class="blocktitle">'.$this->_tpl_vars['jieqi_pageblocks']['61']['title'].'</div>
	<div class="blockcontent">'.$this->_tpl_vars['jieqi_pageblocks']['61']['content'].'</div>
</div>

';
?>