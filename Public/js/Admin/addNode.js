document.getElementById("submit").addEventListener("click",addNode);
ajax({
	url: root_url+"/Admin/Node/getAllPid",              //请求地址
	type: "GET",                       //请求方式
	dataType:'JSON',
	success: function (data) {
		// 此处放成功后执行的代码
		data = JSON.parse(data);
		var content = document.getElementById("pid").innerHTML;
		for(var i =0;i<data.length;i++)
		{
			content +="<option value=\""+data[i].id+"\">"+data[i].id+"("+data[i].remark+")</option>";
		}
		document.getElementById("pid").innerHTML = content;
	},
	fail: function (status) {
		// 此处放失败后执行的代码
		alert(status);
	}
});
function addNode(){
	// document.getElementById('iframe').contentWindow.document.getElementById('status').value
	document.getElementById("submit").disabled=true;
	var name  = document.getElementById("name").value;
	var title  = document.getElementById("title").value;
	var status  = document.getElementById("status").value;
	var display  = document.getElementById("display").value;
	var remark  = document.getElementById("remark").value;
	var sort  = document.getElementById("sort").value;
	var pid  = document.getElementById("pid").value;
	var level  = document.getElementById("level").value;
	ajax({
		url: root_url+"/Admin/Node/addNode",              //请求地址
		type: "POST",                       //请求方式
		data:{
			name:name,
			title:title,
			status:status,
			display:display,
			remark:remark,
			sort:sort,
			pid:pid,
			level:level,
		},
		dataType:'JSON',
		success: function (data) {
			// 此处放成功后执行的代码
			data = JSON.parse(data);
			if(data.status==true){
				alert("添加成功");
				location.href = root_url+"/Admin/Node/";
			}else{
				alert(data.info)
			}
		},
		fail: function (status) {
			// 此处放失败后执行的代码
			alert(status);
			document.getElementById("submit").disabled=false;
		}
	});
}