$(document).ready(function(){function e(){switch(d){case b.Level:return{Name:b.Name,IsLast:!0};case c.Level:return{Name:c.Name,IsLast:!0};default:return[]}}var b={Level:1,Name:"\u0421\u0432\u043e\u0434\u043d\u0430\u044f \u0442\u0430\u0431\u043b\u0438\u0446\u0430 \u0440\u0430\u0441\u043f\u0440\u0435\u0434\u0435\u043b\u0435\u043d\u0438\u044f"},c={Level:35,Name:"\u041a\u043b\u0438\u0435\u043d\u0442\u044b &harr; \u0421\u0430\u0439\u0442\u044b"},d=0,f={data:[{Level:b.Level,Name:b.Name,IsDoc:!0},{Level:c.Level,
Name:c.Name,IsDoc:!0}]};$.ReportListTranslate={Init:function(){this.InitActions();this.InitTemplate();this.InitDynamicData()},InitActions:function(){$(document).on("click",".report-folder>a, .report-bread",function(a){a=$(a.target).closest("[level]");d=parseInt(a.attr("level"));$.ReportListTranslate.ReloadReportList()})},InitDynamicData:function(){$.ReportListTranslate.ReloadReportList()},InitTemplate:function(){$("#reportsTemplate").template("reportsTemplate")},ReloadReportList:function(){function a(a){$("#reports").empty();
$.tmpl("reportsTemplate",a).appendTo("#reports")}$("#reports").html("\u0417\u0430\u0433\u0440\u0443\u0437\u043a\u0430 \u0434\u0430\u043d\u043d\u044b\u0445...");switch(d){case 0:a(f);break;case b.Level:a({bread:e()});break;case c.Level:a({bread:e()});break;default:alert("\u041e\u0448\u0438\u0431\u043a\u0430 \u0437\u0430\u0433\u0440\u0443\u0437\u043a\u0438 \u0434\u0430\u043d\u043d\u044b\u0445")}$.ReportListTranslate.ShowReport(d)},ShowReport:function(a){$("#ReportOverallAllocation").toggle(a==b.Level);
$("#ReportOverallCustomersSites").toggle(a==c.Level);switch(a){case b.Level:$("#overallAllocationSite").find("input:first").click();break;case c.Level:$("#overallAllocationSite").find("input:first").click()}}};$.ReportListTranslate.Init()});
