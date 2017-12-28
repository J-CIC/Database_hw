$(document).ready(function(){

	//chart图表对象初始化
	var goods_id_charts = echarts.init(document.getElementById("goods_id_charts"));
	var amount_id_charts = echarts.init(document.getElementById("amount_id_charts"));
	var item_customer_charts = echarts.init(document.getElementById("item_customer_charts"));
	var item_customer_prov_charts = echarts.init(document.getElementById("item_customer_prov_charts"));
	var item_customer_city_charts = echarts.init(document.getElementById("item_customer_city_charts"));

	//功能div初始化
	clearSelect();
	function clearSelect(){
		$("#goods_id").hide();
		$("#amount_id").hide();
		$("#item_customer").hide();
		$("#item_customer_prov").hide();
		$("#item_customer_city").hide();
		$("#customer_id").hide();
		$("#orderway_id").hide();
	}
	$('#type').on('click','li',function(){
		var id = $(this).attr('id');
		$("#type li").attr("class","");
		$(this).attr("class","active");
		clearSelect();
		if(id=="payRatio"){
			$("#goods_id").show();
			setGoods();
		}else if(id=="totalAmount"){
			$("#amount_id").show();
			setTotalAmount();
			setAmountAnalysis();
		}else if(id=="customerAnalysis"){
			$("#customer_id").show();
			$("#item_customer").show();
			setCustomerAnalysis();
		}else if(id=="orderWay"){
			$("#orderway_id").show();
			setOrderWayTable();
		}
		$(".province-select").each(function(){
			$(this).html(window.province_name);
		});
	});

	//支付比率分析
	function setGoods(){
		goods_id_charts.resize($("#goods_id_charts").width());
		goods_id_charts.showLoading();
		$.ajax({
			type: 'POST',
			url: "itemAnalysis",
			data: {
				by:"goods_id",
				province:window.province
			},
			success: function(data, textStatus){
				var arr = data.data;
				setGoodsImage(arr);
			},
			error:function(XMLHttpRequest, textStatus, errorThrown){
				console.log(textStatus);
			},
			dataType: "JSON"
		});
		function setGoodsImage(arr){
			var dataAxis = [];
			var data = [];
			for (var i = 0; i <= arr.length - 1; i++) {
				dataAxis.push(arr[i]['name'])
				data.push(arr[i]['pay_ratio'])
			}

			option = {
				color: ['#3398DB'],
				title: {
					text: '支付比率最低的商品',
					subtext: '支付成功与订单总数的比例'
				},
				tooltip : {
					trigger: 'axis',
					axisPointer : {			// 坐标轴指示器，坐标轴触发有效
						type : 'shadow'		// 默认为直线，可选为：'line' | 'shadow'
					}
				},
				grid: {
					left: '3%',
					right: '4%',
					bottom: '3%',
					containLabel: true
				},
				xAxis : [
					{
						type : 'category',
						data : dataAxis,
						axisTick: {
							alignWithLabel: true
						},
						show:false
					}
				],
				yAxis : [
					{
						type : 'value'
					}
				],
				series : [
					{
						name:'支付比率',
						type:'bar',
						barWidth: '60%',
						data:data
					}
				]
			};
			goods_id_charts.setOption(option);
			goods_id_charts.hideLoading();
		}
	}


	//总销售额分析
	function setTotalAmount(){
		amount_id_charts.resize($("#amount_id").width());
		amount_id_charts.showLoading();
		$.ajax({
			type: 'POST',
			url: "itemAnalysis",
			data: {
				by:"amount_id",
				province:window.province
			},
			success: function(data, textStatus){
				var arr = data.data;
				setGoodsImage(arr);
			},
			error:function(XMLHttpRequest, textStatus, errorThrown){
				console.log(textStatus);
			},
			dataType: "JSON"
		});
		function setGoodsImage(arr){
			var dataAxis = [];
			var data = [];
			for (var i = 0; i <= arr.length - 1; i++) {
				dataAxis.push(arr[i]['name'])
				data.push(arr[i]['total_money']/100)
			}

			option = {
				color: ['#3398DB'],
				title: {
					text: '终端销售额Top10',
					subtext: '单位：元'
				},
				tooltip : {
					trigger: 'axis',
					axisPointer : {			// 坐标轴指示器，坐标轴触发有效
						type : 'shadow'		// 默认为直线，可选为：'line' | 'shadow'
					}
				},
				grid: {
					left: '3%',
					right: '4%',
					bottom: '3%',
					containLabel: true
				},
				xAxis : [
					{
						type : 'category',
						data : dataAxis,
						axisTick: {
							alignWithLabel: true
						},
						show:false
					}
				],
				yAxis : [
					{
						type : 'value'
					}
				],
				series : [
					{
						name:'销售额',
						type:'bar',
						barWidth: '60%',
						data:data,
					}
				]
			};
			amount_id_charts.setOption(option);
			amount_id_charts.hideLoading();
		}
	}

	//销售额——时间分析
	function setAmountAnalysis(){
		var mycharts = echarts.init(document.getElementById("charts"));
	    var mycharts2 = echarts.init(document.getElementById("charts2"));
	    mycharts2.showLoading();
	    mycharts.showLoading();
	    $.ajax({
	        type: 'POST',
	        url: "OrderTimeDistribution",
	        data: {
	            province:window.province
	        },
	        success: function(data, textStatus){

	            console.info(data);
	            var times = data.times;
	            data = data.ranks;
	            var x = [];
	            var y = [];
	            for(var i=data.length-1;i>=0;i--){
	                x.push(data[i].pay_sum/100)
	                y.push(data[i].times)
	            }
	            var option = {
	                title: {
	                    text: '销售额排行图',
	                    subtext: '以天为单位'
	                },
	                tooltip: {
	                    trigger: 'axis',
	                    axisPointer: {
	                        type: 'shadow'
	                    }
	                },
	                grid: {
	                    left: '3%',
	                    right: '4%',
	                    bottom: '3%',
	                    containLabel: true
	                }, 
	                toolbox: {
	                    feature: {
	                        saveAsImage: {}
	                    }
	                },
	                xAxis: {
	                    type: 'value',
	                    boundaryGap: [0, 0.01],
	                    axisLabel:{
	                        formatter:"{value}元"
	                    }
	                },
	                yAxis: {
	                    type: 'category',
	                    data: y
	                },
	                series: [
	                    {
	                        name: '销量',
	                        type: 'bar',
	                        data: x,
	                        itemStyle:{
	                            normal:{
	                                color:'#337ab7',
	                                formatter:'{c}元'
	                            }
	                        },
	                        label:{
	                            normal:{
	                                show:true,
	                                formatter:'{c}元'
	                            }
	                        }
	                    },
	                ]
	            };
	            mycharts.hideLoading();
	            mycharts.setOption(option);
	            setImage(times);
	        },
	        error:function(XMLHttpRequest, textStatus, errorThrown){
	            console.log(textStatus);
	        },
	        dataType: "JSON"
	    });
	    function setImage(data){
	        var x = [];
	        var y = [];
	        for(var i=0;i<data.length;i++){
	            y.push(data[i].pay_sum/100)
	            x.push(data[i].times)
	        }
	        option = {
	            title: {
	                text: '全月销售额'
	            },
	            tooltip: {
	                trigger: 'axis'
	            },
	            legend: {
	                data:['销售额']
	            },
	            grid: {
	                left: '3%',
	                right: '4%',
	                bottom: '3%',
	                containLabel: true
	            },
	            toolbox: {
	                feature: {
	                    saveAsImage: {}
	                }
	            },
	            xAxis: {
	                type: 'category',
	                boundaryGap: false,
	                data: x
	            },
	            yAxis: {
	                type: 'value'
	            },
	            series: [
	                {
	                    name:'销售额',
	                    type:'line',
	                    stack: '总量',
	                    data:y
	                },
	            ]
	        };
	        mycharts2.hideLoading();
	        mycharts2.setOption(option);
	    }
	}

	//终端受众分析
	function setCustomerAnalysis(){	
		
		allOptions = new Array();
		allOptions["op"] = 0;
		getData(allOptions);
		function getData(allOptions){	//op取值0、1、2，分别代表终端受众度top10操作、某终端受众度全国分布、某终端受众度省份分布
			var op = allOptions["op"];
			var params;
			if(op==0){
				item_customer_charts.showLoading();
				params = {
					by:"item_customer",
					op:op
				};
			}else if(op==1){
				item_customer_prov_charts.showLoading();
				params = {
					by:"item_customer",
					op:op,
					item_name:allOptions["item_name"]
				};
			}else if(op==2){
				$("#item_customer_city_title").html("终端受众度Top10>"+allOptions["prov_nm"]+"省分布");
				item_customer_city_charts.showLoading();
				params = {
					by:"item_customer",
					op:op,
					item_name:allOptions["item_name"],
					prov_nm:allOptions["prov_nm"]
				};
			}
			$.ajax({
				type: 'POST',
				url: "itemAnalysis",
				data: params,
				success: function(data, textStatus){
					var arr = data.data;
					console.info(data);
					setGoodsImage(op,arr);
				},
				error:function(XMLHttpRequest, textStatus, errorThrown){
					console.log(textStatus);
				},
				dataType: "JSON"
			});
			isFirst = 0;
		}
		function setGoodsImage(op,arr){
			if(op==0){
				var ylabel = [];
				var xdata = [];
				for(var i=0;i<arr.length;i++){
					ylabel.push(arr[i]["name"]);
					xdata.push(arr[i]["customer_count"]);
				}
				var option = {
			        tooltip: {
			            trigger: 'axis',
			            axisPointer: {
			                type: 'shadow'
			            }
			        },
			        grid: {
			            left: '3%',
			            right: '4%',
			            bottom: '3%',
			            containLabel: true
			        },
			        xAxis: {
			            type: 'value',
			            axisLine: {
			                show: false
			            },
			            axisTick: {
			                show: false
			            }
			        },
			        yAxis: {
			            type: 'category',
			            data: ylabel,
			            splitLine: {show: false},
			            axisLine: {
			                show: false
			            },
			            axisTick: {
			                show: false
			            },
			            offset: 10,
			            nameTextStyle: {
			                fontSize: 15
			            }
			        },
			        toolbox: {
		                feature: {
		                    saveAsImage: {}
		                }
		            },
			        series: [
			            {
			                name: '数量',
			                type: 'bar',
			                data: xdata,
			                barWidth: 14,
			                barGap: 10,
			                smooth: true,
			                label: {
			                    normal: {
			                        show: true,
			                        position: 'right',
			                        offset: [5, -2],
			                        textStyle: {
			                            color: '#F68300',
			                            fontSize: 13
			                        }
			                    }
			                },
			                itemStyle: {
			                    emphasis: {
			                        barBorderRadius: 7
			                    },
			                    normal: {
			                        barBorderRadius: 7,
			                        color: new echarts.graphic.LinearGradient(
			                            0, 0, 1, 0,
			                            [
			                                {offset: 0, color: '#3977E6'},
			                                {offset: 1, color: '#37BBF8'}

			                            ]
			                        )
			                    }
			                }
			            }
			        ]
			    };
			}else{
				if(op==1){
					var type = "province_id";
				}else{
					var type = "city_id";
				}
				var dataAxis = [];
				var idList = [];
				var data = [];

				for (var i = 0; i < arr.length; i++) {

				    //控制显示20个省份/城市，超出显示为其他
				    if(i<20){
				        dataAxis.push(arr[i][(type=='province_id')?'prov_nm':'id_area_nm']);
				        idList.push(arr[i][type]);
				        data.push(parseInt(arr[i]["count"]));
				    }
				    
				}

				option = {
				    tooltip: {},
				    xAxis: {
				        data: dataAxis,
				        axisTick: {
				            show: true
				        },
				        axisLine: {
				            show: true
				        },
				        z: 10
				    },
				    grid: {
				        left: '3%',
				        right: '4%',
				        bottom: '3%',
				        top: '3%',
				        containLabel: true
				    },
				    yAxis: {
				        axisLine: {
				            show: true
				        },
				        axisTick: {
				            show: true
				        },
				        axisLabel: {
				            textStyle: {
				                color: '#999'
				            }
				        }
				    },
				    toolbox: {
		                feature: {
		                    saveAsImage: {}
		                }
		            },
				    dataZoom: [
				        {
				            type: 'inside'
				        }
				    ],
				    series: [
				        { // For shadow
				            type: 'bar',
				            itemStyle: {
				                normal: {color: 'rgba(0,0,0,0.05)'}
				            },
				            barGap:'-100%',
				            barCategoryGap:'40%',
				            data: data,
				            animation: false
				        },
				        {
				            type: 'bar',
				            itemStyle: {
				                normal: {
				                    color: new echarts.graphic.LinearGradient(
				                        0, 0, 0, 1,
				                        [
				                            {offset: 0, color: '#83bff6'},
				                            {offset: 0.5, color: '#188df0'},
				                            {offset: 1, color: '#188df0'}
				                        ]
				                    )
				                },
				                emphasis: {
				                    color: new echarts.graphic.LinearGradient(
				                        0, 0, 0, 1,
				                        [
				                            {offset: 0, color: '#2378f7'},
				                            {offset: 0.7, color: '#2378f7'},
				                            {offset: 1, color: '#83bff6'}
				                        ]
				                    )
				                }
				            },
				            data: data
				        }
				    ]
				};
			}
			if(op==0){
				item_customer_charts.setOption(option);
				item_customer_charts.hideLoading();
			}else if(op==1&&window.province==1){
				item_customer_prov_charts.setOption(option);
				item_customer_prov_charts.hideLoading();
			}else if(op==1&&window.province!=1){
				item_customer_city_charts.setOption(option);
				item_customer_city_charts.hideLoading();
			}else if(op==2){
				item_customer_city_charts.setOption(option);
				item_customer_city_charts.hideLoading();
			}
			//点击终端受众度
	        item_customer_charts.on('click', function (params) {
	        	
	        	$($(".item_name")[0]).html(params.name);
	        	all_options = new Array();
	        	if(window.province == 1){		//查看当前选中是否为全国
	        		$("#item_customer_prov").show();
	        		$("#item_customer_city").hide();
	        		all_options["op"] = 1;
		        	all_options["item_name"] = params.name;
		        	$($(".item_name")[0]).html(params.name);
		        	getData(all_options);
	        	}else{							//选中为其他省份
	        		$("#item_customer_prov").hide();
	        		$("#item_customer_city").show();
	        		all_options["op"] = 2;
		        	all_options["item_name"] = params.name;
		        	$($(".item_name")[1]).html(params.name);
		        	all_options['prov_nm'] = window.province_name;
		        	getData(all_options);
	        	}
	        });
	        //点击终端受众度全国分布查看省份分布
	        item_customer_prov_charts.on('click', function (params){
	        	$("#item_customer_city").show();	
	        	all_options["op"] = 2;
	        	all_options["item_name"] = $($(".item_name")[0]).html();
	        	all_options['prov_nm'] = params.name;
	        	getData(all_options);
	        });
		}
	}

	//动态加载商品销售渠道特征分析表格
	function setOrderWayTable(){
		var $img = $("<img src='http://image.sixseven.cn/myweb/loading.gif' style='width:20px;height:20px'>");
		var $tr = $("<tr style='line-height:100px;' class='center-block'></tr>");
		$tr.append($img);
		$("#orderway_id table").append($tr);

		$.ajax({
			type: 'POST',
			url: "orderWay",
			data:{
				'op':4,
				'date':window.date,
				'province':window.province
			},
			success: function(data, textStatus){
				var arr = data.data;

				$("#orderway_id table").empty();

				$thead = $("<thead><tr><td style='width:10%'>移动商城</td><td style='width:10%'>手机营业厅</td><td style='width:10%'>触屏版商城</td><td style='width:10%'>用户数</td>"+"<td style='width:50%'>占比</td><td style='width:10%'>下载</td></tr></thead>");
				$tbody = $("<tbody></tbody>");

				var all_count = 0;
				for(var i=0;i<arr.length;i++){
					all_count += parseInt(arr[i]["count"]);
				}
				$tr = $("<tr><td colspan='3'>合计</td><td>"+all_count+"</td><td></td><td><span class='download fa fa-cloud-download' data-index='0' type='submit'></span></td></tr>");
				$tbody.append($tr);

				for(var i=0;i<arr.length;i++){
					var rate = parseInt(arr[i]["count"])*100/all_count;
					if(arr[i]["order_way"]==1){
						$tr = $("<tr><td class='display_td' data-index='0'  style='background:#3399FF;cursor:pointer;'></td><td></td><td></td><td>"+arr[i]["count"]+"</td><td>"+rate.toFixed(2)+"%<div class='bar'><div class='active_bar' style='width:"+parseInt(rate)+"%'></div></div></td><td><span class='download glyphicon glyphicon-download-alt' data-index='1'></span></td></tr>");
					}else if(arr[i]["order_way"]==2){
						$tr = $("<tr><td></td><td class='display_td' data-index='1' style='background:#3399FF;cursor:pointer;'></td><td></td><td>"+arr[i]["count"]+"</td><td>"+rate.toFixed(2)+"%<div class='bar'><div class='active_bar' style='width:"+parseInt(rate)+"%'></div></div></td><td><span class='download glyphicon glyphicon-download-alt' data-index='2'></span></td></tr>");
					}else{
						$tr = $("<tr><td></td><td></td><td class='display_td' data-index='2' style='background:#3399FF;cursor:pointer;'></td><td>"+arr[i]["count"]+"</td><td>"+rate.toFixed(2)+"%<div class='bar'><div class='active_bar' style='width:"+parseInt(rate)+"%'></div></div></td><td><span class='download glyphicon glyphicon-download-alt' data-index='3'></span></td></tr>");
					}
					$tbody.append($tr);
				}
				$("#orderway_id table").append($thead);
				$("#orderway_id table").append($tbody);
			},
			error:function(XMLHttpRequest, textStatus, errorThrown){
				console.log(textStatus);
			},
			dataType: "JSON"

		});
	}

	//点击下载按钮下载数据
	$("#orderway_id table").on("click",".download",function(){
		var op = $(this).data("index");
		var params = new Array();
		params["op"] = op;
		post('orderWay',params);
	});
	function post(URL, PARAMS) { 
		var temp_form = document.createElement("form");      
	    temp_form .action = URL;      
	    temp_form .target = "_blank";
	    temp_form .method = "post";      
	    temp_form .style.display = "none"; 
	    for (var x in PARAMS) { 
	    	var opt = document.createElement("textarea");      
	        opt.name = x;      
	        opt.value = PARAMS[x];      
	        temp_form .appendChild(opt);      
	    }  
	    var opt = document.createElement("textarea"); 
	    opt.name = "province";      
	    opt.value = window.province;      
	    temp_form .appendChild(opt);      
	    document.body.appendChild(temp_form);      
	    temp_form .submit();     
	} 

	//点击商品销售渠道，显示相关渠道对应的省份和城市分析
	$("#orderway_id table").on("click",".display_td",function(){
		var index = $(this).data("index");
		$(this).css("background","#00a67c");
		$("#orderway_id table .display_td").each(function(){
			if($(this).data("index")!=index){
				$(this).css("background","#3399FF");
			}
		});
		if(window.province==1){		//当前选中全国，可以查看全部省份数据
			getChart();
	    	//getChart('city_id',window.province,window.province_name);
		}else{	//选中省份
			getChart('city_id',window.province,window.province_name);
		}
	});


	//获取数据
	function getChart(type="province_id",id=0,name='省份'){     //默认为全国分布，若为特定省份分布时，id值为省份id

	    if(type=="province_id"){
	    	$("#order_by_province").css("display","block");
	        var chart = echarts.init(document.getElementById('province_chart'));
	    }else{
	    	$("#order_by_city").css("display","block");
	        var chart = echarts.init(document.getElementById('city_chart'));
	        $("#order_by_city .title").html("<span>用户群体分析</span>&nbsp;&gt;&nbsp;<span>"+name+"省分布</span>");
	    }
	    chart.showLoading();

	    $.ajax({
	        type: 'POST',
	        url: "OrderByLocation",
	        data: {
	            type:type,
	            id:id,
	            province:window.province
	        },
	        success: function(response, textStatus){

	            var response = eval(response);

	            setChart(chart,response,type);

	        },
	        error:function(XMLHttpRequest, textStatus, errorThrown){
	            console.log(textStatus);
	        },
	        dataType: "JSON"
	    });
	}

	//构建chart
	function setChart(chart,response,type){     //chart为图表节点对象，response为数据，type为数据类型，取值为province_id和city_id

	    var dataAxis = [];
	    var idList = [];
	    var data = [];

	    for (var i = 0; i < response.length; i++) {

	        //控制显示20个省份/城市，超出显示为其他
	        if(i<20){
	            dataAxis.push(response[i][(type=='province_id')?'prov_nm':'id_area_nm']);
	            idList.push(response[i][type]);
	            data.push(parseInt(response[i]["count"]));
	        }
	        
	    }

	    option = {
	        tooltip: {},
	        xAxis: {
	            data: dataAxis,
	            axisTick: {
	                show: true
	            },
	            axisLine: {
	                show: true
	            },
	            z: 10
	        },
	        grid: {
	            left: '3%',
	            right: '4%',
	            bottom: '3%',
	            top: '3%',
	            containLabel: true
	        },
	        yAxis: {
	            axisLine: {
	                show: true
	            },
	            axisTick: {
	                show: true
	            },
	            axisLabel: {
	                textStyle: {
	                    color: '#999'
	                }
	            }
	        },
	        toolbox: {
	            feature: {
	                saveAsImage: {}
	            }
	        },
	        dataZoom: [
	            {
	                type: 'inside'
	            }
	        ],
	        series: [
	            { // For shadow
	                type: 'bar',
	                itemStyle: {
	                    normal: {color: 'rgba(0,0,0,0.05)'}
	                },
	                barGap:'-100%',
	                barCategoryGap:'40%',
	                data: data,
	                animation: false
	            },
	            {
	                type: 'bar',
	                itemStyle: {
	                    normal: {
	                        color: new echarts.graphic.LinearGradient(
	                            0, 0, 0, 1,
	                            [
	                                {offset: 0, color: '#83bff6'},
	                                {offset: 0.5, color: '#188df0'},
	                                {offset: 1, color: '#188df0'}
	                            ]
	                        )
	                    },
	                    emphasis: {
	                        color: new echarts.graphic.LinearGradient(
	                            0, 0, 0, 1,
	                            [
	                                {offset: 0, color: '#2378f7'},
	                                {offset: 0.7, color: '#2378f7'},
	                                {offset: 1, color: '#83bff6'}
	                            ]
	                        )
	                    }
	                },
	                data: data
	            }
	        ]
	    };
	    chart.setOption(option);
	    chart.hideLoading();

	    if(type=="province_id"){
	        //点击省份数据查看省份分布
	        chart.on('click', function (params) {
	            if(params.dataIndex != 20){
	                var province_id = idList[params.dataIndex];
	                var prov_nm = dataAxis[params.dataIndex];
	                getChart("city_id",province_id,prov_nm);
	            }
	        });
	    }
	}

});