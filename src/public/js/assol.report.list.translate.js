$(document).ready(function(){function k(){switch(h){case b.Level:return{Name:b.Name,IsLast:!0};case c.Level:return{Name:c.Name,IsLast:!0};case d.Level:return{Name:d.Name,IsLast:!0};case e.Level:return{Name:e.Name,IsLast:!0};case f.Level:return{Name:f.Name,IsLast:!0};case g.Level:return{Name:g.Name,IsLast:!0};default:return[]}}var b={Level:1,Name:"\u0415\u0436\u0435\u0434\u043d\u0435\u0432\u043d\u044b\u0439 \u043e\u0442\u0447\u0435\u0442"},d={Level:2,Name:"\u041e\u0442\u0447\u0435\u0442 \u043f\u043e \u043f\u0435\u0440\u0435\u043f\u0438\u0441\u043a\u0435"},
c={Level:3,Name:"\u041e\u0442\u0447\u0435\u0442 \u043f\u043e \u0440\u0430\u0441\u0441\u044b\u043b\u043a\u0435"},e={Level:4,Name:"\u041e\u0442\u0447\u0435\u0442 \u043f\u043e \u0437\u0430\u0440\u043f\u043b\u0430\u0442\u0435"},f={Level:5,Name:"\u041f\u043e\u0434\u0442\u0432\u0435\u0440\u0436\u0434\u0435\u043d\u043d\u0430\u044f \u0437\u0430\u0440\u043f\u043b\u0430\u0442\u0430"},g={Level:6,Name:"\u0417\u0432\u043e\u043d\u043a\u0438"},h=0,l={data:[{Level:b.Level,Name:b.Name,IsDoc:!0},{Level:d.Level,Name:d.Name,
IsDoc:!0},{Level:c.Level,Name:c.Name,IsDoc:!0},{Level:e.Level,Name:e.Name,IsDoc:!0},{Level:f.Level,Name:f.Name,IsDoc:!0},{Level:g.Level,Name:g.Name,IsDoc:!0}]};$.ReportListTranslate={Init:function(){this.InitActions();this.InitTemplate();this.InitDynamicData()},InitActions:function(){$(document).on("click",".report-folder>a, .report-bread",function(a){a=$(a.target).closest("[level]");h=parseInt(a.attr("level"));$.ReportListTranslate.ReloadReportList()})},InitDynamicData:function(){$.ReportListTranslate.ReloadReportList()},
InitTemplate:function(){$("#reportsTemplate").template("reportsTemplate")},ReloadReportList:function(){function a(a){$("#reports").empty();$.tmpl("reportsTemplate",a).appendTo("#reports")}$("#reports").html("\u0417\u0430\u0433\u0440\u0443\u0437\u043a\u0430 \u0434\u0430\u043d\u043d\u044b\u0445...");switch(h){case 0:a(l);break;case b.Level:case c.Level:case d.Level:case e.Level:case f.Level:case g.Level:a({bread:k()});break;default:alert("\u041e\u0448\u0438\u0431\u043a\u0430 \u0437\u0430\u0433\u0440\u0443\u0437\u043a\u0438 \u0434\u0430\u043d\u043d\u044b\u0445")}$.ReportListTranslate.ShowReport(h)},
ShowReport:function(a){$("#ReportIndividualDaily").toggle(a==b.Level);$("#ReportIndividualMailing").toggle(a==c.Level);$("#ReportIndividualCorrespondence").toggle(a==d.Level);$("#ReportIndividualSalary").toggle(a==e.Level);$("#ReportApprovedSalary").toggle(a==f.Level);$("#ReportCalls").toggle(a==g.Level);switch(a){case b.Level:$.ReportTranslate.ReloadReportDailyMeta();break;case c.Level:$.ReportTranslate.ReloadReportMailing();break;case d.Level:$.ReportTranslate.ReloadReportCorrespondence();break;
case e.Level:$.ReportTranslate.ReloadReportSalary();break;case f.Level:$.ReportTranslate.ReloadReportApprovedSalary();break;case g.Level:loadCalls()}}};$.ReportListTranslate.Init()});
