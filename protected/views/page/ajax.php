<?php
?>
<a id="ajaxRequest" href="javascript:;">点击ajax请求</a>
<script src="/js/jquery-1.10.2.js"></script>
<script>
$('#ajaxRequest').click(function (e) {
    e.preventDefault();
    $.ajax({
        type     : 'POST',
        url      : '<?php echo $this->createUrl('/page/ajax');?>',
        dataType : 'text',
        data     : {'menus':[{name:'name1',value:'value1'},{name:'name2','subs':[{name:'subs1name', value:'subs1value'},{name:'subs2name', value:'subs2value'}]},{name:'name3',value:'value3'}]},
        success  : function (response) {
        	alert(response);
        }
    });
    
});
</script>