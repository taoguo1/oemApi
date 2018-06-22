<script type="text/javascript" src="/Static/js/api.js"></script>
<script>
setTimeout(function()
{
	api.execScript(
	{
		name:api.winName,
		script:"paySuccess('<?php echo $pars?>')"
	});
},200);
</script>