$(document).ready(function(){function x(){var a=[{Level:c.Level,Name:c.Name},{Level:u.Level,Name:u.Name}];switch(b){case e.Level:a.push({Name:e.Name});break;case f.Level:a.push({Name:f.Name});break;case g.Level:a.push({Name:g.Name});break;case h.Level:a.push({Name:h.Name})}a[a.length-1].IsLast=!0;return a}function z(){var a=[{Level:t.Level,Name:t.Name}];switch(b){case k.Level:a.push({Name:k.Name,IsLast:!0});break;case l.Level:a.push({Name:l.Name,IsLast:!0});break;case m.Level:a.push({Name:m.Name,
IsLast:!0});break;case n.Level:a.push({Name:n.Name,IsLast:!0});break;case p.Level:a.push({Name:p.Name,IsLast:!0});break;case q.Level:a.push({Name:q.Name,IsLast:!0});break;case r.Level:a.push({Name:r.Name,IsLast:!0})}return a}var c={Level:1,Name:"\u0418\u043d\u0434\u0438\u0432\u0438\u0434\u0443\u0430\u043b\u044c\u043d\u044b\u0435"},u={Level:2,Name:function(){return v}},e={Level:21,Name:"\u0415\u0436\u0435\u0434\u043d\u0435\u0432\u043d\u044b\u0439 \u043e\u0442\u0447\u0435\u0442"},g={Level:22,Name:"\u041e\u0442\u0447\u0435\u0442 \u043f\u043e \u043f\u0435\u0440\u0435\u043f\u0438\u0441\u043a\u0435"},
f={Level:23,Name:"\u041e\u0442\u0447\u0435\u0442 \u043f\u043e \u0440\u0430\u0441\u0441\u044b\u043b\u043a\u0435"},h={Level:24,Name:"\u041e\u0442\u0447\u0435\u0442 \u043f\u043e \u0437\u0430\u0440\u043f\u043b\u0430\u0442\u0435"},t={Level:3,Name:"\u041e\u0431\u0449\u0438\u0435"},k={Level:31,Name:"\u041f\u043e\u0434\u0442\u0432\u0435\u0440\u0436\u0434\u0435\u043d\u043d\u0430\u044f \u0437\u0430\u0440\u043f\u043b\u0430\u0442\u0430"},n={Level:32,Name:"\u0412\u044b\u043f\u043b\u0430\u0442\u0430 \u0437\u0430\u0440\u043f\u043b\u0430\u0442\u044b"},
m={Level:33,Name:"\u041e\u0431\u0449\u0430\u044f \u0442\u0430\u0431\u043b\u0438\u0446\u0430 \u043f\u043e \u043a\u043b\u0438\u0435\u043d\u0442\u0430\u043c"},l={Level:34,Name:"\u0421\u0432\u043e\u0434\u043d\u0430\u044f \u0442\u0430\u0431\u043b\u0438\u0446\u0430 \u0440\u0430\u0441\u043f\u0440\u0435\u0434\u0435\u043b\u0435\u043d\u0438\u044f"},p={Level:35,Name:"\u041a\u043b\u0438\u0435\u043d\u0442\u044b &harr; \u0421\u0430\u0439\u0442\u044b"},q={Level:36,Name:"\u0415\u0436\u0435\u0434\u043d\u0435\u0432\u043d\u044b\u0439 \u043e\u0442\u0447\u0435\u0442 \u043f\u043e \u0441\u043e\u0442\u0440\u0443\u0434\u043d\u0438\u043a\u0430\u043c"},
r={Level:37,Name:"\u0417\u0432\u043e\u043d\u043a\u0438"},b=0,v="",d=0,A={data:[{Level:c.Level,Name:c.Name.toUpperCase()},{Level:t.Level,Name:t.Name.toUpperCase()}]},B={bread:[{Name:t.Name,IsLast:!0}],data:[{Level:k.Level,Name:k.Name,IsDoc:!0},{Level:n.Level,Name:n.Name,IsDoc:!0},{Level:m.Level,Name:m.Name,IsDoc:!0},{Level:l.Level,Name:l.Name,IsDoc:!0},{Level:p.Level,Name:p.Name,IsDoc:!0},{Level:q.Level,Name:q.Name,IsDoc:!0},{Level:r.Level,Name:r.Name,IsDoc:!0}]},C={bread:x,data:[{Level:e.Level,Name:e.Name,
IsDoc:!0},{Level:g.Level,Name:g.Name,IsDoc:!0},{Level:f.Level,Name:f.Name,IsDoc:!0},{Level:h.Level,Name:h.Name,IsDoc:!0}]};$.ReportListDirector={Init:function(){this.InitActions();this.InitTemplate();this.InitDynamicData()},InitActions:function(){$(document).on("click",".report-folder>a, .report-bread",function(a){a=$(a.target).closest("[level]");b=parseInt(a.attr("level"));if(b==u.Level){var c=a.find(".folder-name").html();c&&(v=c);(a=a.attr("id-employee"))&&(d=a)}$.ReportListDirector.ReloadReportList()})},
InitDynamicData:function(){this.ReloadReportList()},InitTemplate:function(){$("#reportsTemplate").template("reportsTemplate")},ReloadReportList:function(){function a(a){$("#reports").empty();$.tmpl("reportsTemplate",a).appendTo("#reports")}function d(b){b.status?b.records&&a({bread:[{Level:c.Level,Name:c.Name,IsLast:!0}],data:b.records}):showErrorAlert(b.message)}$("#reports").html("\u0417\u0430\u0433\u0440\u0443\u0437\u043a\u0430 \u0434\u0430\u043d\u043d\u044b\u0445...");switch(b){case 0:a(A);break;
case c.Level:$.post(BaseUrl+"reports/data",{},d,"json");break;case t.Level:a(B);break;case u.Level:a(C);break;case e.Level:case f.Level:case g.Level:case h.Level:a({bread:x()});break;case k.Level:case l.Level:case m.Level:case n.Level:case p.Level:case q.Level:case r.Level:a({bread:z()});break;default:alert("\u041e\u0448\u0438\u0431\u043a\u0430 \u0437\u0430\u0433\u0440\u0443\u0437\u043a\u0438 \u0434\u0430\u043d\u043d\u044b\u0445")}$.ReportListDirector.ShowReport(b)},ShowReport:function(a){$("#ReportIndividualDaily").toggle(a==
e.Level);$("#ReportIndividualMailing").toggle(a==f.Level);$("#ReportIndividualCorrespondence").toggle(a==g.Level);$("#ReportIndividualSalary").toggle(a==h.Level);$("#ReportOverallSalary").toggle(a==k.Level);$("#ReportOverallAllocation").toggle(a==l.Level);$("#ReportGeneralOfCustomers").toggle(a==m.Level);$("#ReportGeneralSalary").toggle(a==n.Level);$("#ReportOverallCustomersSites").toggle(a==p.Level);$("#ReportDailyEmployees").toggle(a==q.Level);$("#ReportCalls").toggle(a==r.Level);switch(a){case e.Level:$.ReportTranslate.setEmployee(d);
$.ReportTranslate.ReloadReportDailyMeta();break;case f.Level:$.ReportTranslate.setEmployee(d);$.ReportTranslate.ReloadReportMailing();break;case g.Level:$.ReportTranslate.setEmployee(d);$.ReportTranslate.ReloadReportCorrespondence();break;case h.Level:$.ReportTranslate.setEmployee(d);$.ReportTranslate.ReloadReportSalary();break;case k.Level:$("#overlaySalarySite").find("input:first").click();break;case l.Level:$("#overallAllocationSite").find("input:first").click();break;case m.Level:$.ReportDirector.ReloadReportGeneralOfCustomers();
break;case n.Level:$.ReportDirector.ReloadReportGeneralSalary();break;case p.Level:$("#overallAllocationSite").find("input:first").click();break;case q.Level:$("#overallAllocationSite").find("input:first").click();break;case r.Level:$("#overallAllocationSite").find("input:first").click(),loadCalls()}}};$.ReportListDirector.Init();if(""!==window.location.hash){var y=window.location.hash;if(0<y.length){var w=y.replace("#","").split("_");"daily"==w[0]&&$.post("/employee/getname",{ID:w[1]},function(a){""!==
a&&(b=21,v=a,d=w[1],$.ReportListDirector.ReloadReportList())},"text")}}});
