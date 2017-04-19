$(document).ready(function(){$.ReportTranslate={Init:function(){this.InitActions();this.InitTemplate();this.InitDynamicData()},InitActions:function(){function b(a,c,b){var e=c;switch(b||"number"){case "number":c=0<c?c:"";break;case "decimal":c=0<c?c:""}if(null!=$(a).contentEditable)$(a).attr("contentEditable",isEdit);else{c=$("<input/>",{type:"text",min:0,value:c}).attr("last-value",e);$(a).html(c);switch(b||"number"){case "number":c.numeric();break;case "decimal":c.numeric(),c.keydown(function(a){if("188"==
a.keyCode||"188"==a.charCode||"110"==a.keyCode||"110"==a.charCode)a.preventDefault(),a=$(a.target),0<a.val().indexOf(".")||a.val(a.val()+".")})}c.focus()}}$(document).on("click",".is-edit>div",function(){var a=$(this).parent();b(a,a.find("div").html())});$(document).on("focusout",".is-edit>input",function(){function a(a){b.html($("<div/>",{text:a}))}function c(c){c.status?(a(f),$.ReportTranslate.RefreshReportDailyDataSummary()):(a(e),alert("\u041e\u0448\u0438\u0431\u043a\u0430 \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d\u0438\u044f \u0434\u0430\u043d\u043d\u044b\u0445"))}
var b=$(this).parent(),e=parseFloat($(this).attr("last-value")),f=parseFloat($(this).val()||0);if(e!=f){var g={dateRecord:moment([$("#daily-year").data("DateTimePicker").date().year(),$("#daily-month").val(),$("#daily-day").val()]).format("YYYY-MM-DD"),idCross:b.attr("id-cross")};g[b.hasClass("mail")?"mails":"chat"]=f;$.post(BaseUrl+"reports/daily/save",g,c,"json")}else a(e)});$(document).on("click","#mailingSite input:radio",function(a){$.ReportTranslate.ReloadReportMailingMeta($(a.target).val())});
$(document).on("click","#ReportIndividualMailing_data td[day]>div",function(){if(0==$.ReportTranslate.getEmployee()){var a=102==$(this).parent().attr("day");b($(this).parent(),$(this).html(),a?"text":"number")}});$(document).on("focusout","#ReportIndividualMailing_data td[day]>input",function(){function a(a){b.html($("<div/>",{text:a}))}function c(b){b.status?a(g):(a(f),alert("\u041e\u0448\u0438\u0431\u043a\u0430 \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d\u0438\u044f \u0434\u0430\u043d\u043d\u044b\u0445"))}
var b=$(this).parent(),e=parseInt(b.attr("day")),f=parseInt($(this).attr("last-value")||0),g=102!=e?parseInt($(this).val()||0):$(this).val();if(f!=g){var k=$("#mailing-year").data("DateTimePicker").date().year(),l=$("#mailing-month").val(),h={idCross:b.closest("tr").attr("id-cross")};101==e?(h.year=k,h.month=l,h.id=g):102==e?(h.year=k,h.month=l,h.age=g):(h.dateRecord=moment([k,l,e]).format("YYYY-MM-DD"),h.value=g);$.post(BaseUrl+"reports/mailing/save",h,c,"json")}else a(f)});$(document).on("click",
".action-correspondence-remove",function(a){function b(a){a.status?$.ReportTranslate.ReloadReportCorrespondenceMeta():alert("\u041e\u0448\u0438\u0431\u043a\u0430: "+a.message)}var d=$(a.target).closest("[id-record]").attr("id-record");confirmRemove(function(){$.post(BaseUrl+"reports/correspondence/remove",{record:d},b,"json")})});$(document).on("click","#ReportIndividualCorrespondence_data td[day]>div",function(){if(0==$.ReportTranslate.getEmployee()){var a=$(this).parent().attr("day");102==a||104==
a?b($(this).parent(),$(this).html()):b($(this).parent(),$(this).html(),"text")}});$(document).on("focusout","#ReportIndividualCorrespondence_data td[day]>input",function(){function a(a){d.html($("<div/>",{text:a}))}function b(c){c.status?a(k):(a(g),alert("\u041e\u0448\u0438\u0431\u043a\u0430 \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d\u0438\u044f \u0434\u0430\u043d\u043d\u044b\u0445"))}var d=$(this).parent(),e=parseInt(d.attr("day")),f=102==e||104==e,g=f?parseInt($(this).attr("last-value")||
0):$(this).attr("last-value"),k=f?parseInt($(this).val()||0):$(this).val();if(g!=k){var f=$("#correspondence-year").data("DateTimePicker").date().year(),l=$("#correspondence-month").val(),h={idRecord:d.closest("tr").attr("id-record")};if(31>=e)h.dateRecord=moment([f,l,e]).format("YYYY-MM-DD"),h.value=k;else switch(h.year=f,h.month=l,e){case 102:h.idInfo=k;break;case 103:h.menInfo=k;break;case 104:h.idMenInfo=k}$.post(BaseUrl+"reports/correspondence/save",h,b,"json")}else a(g)});$(document).on("click",
"#correspondenceSite input:radio",function(a){$.ReportTranslate.ReloadReportCorrespondenceMeta($(a.target).val())});$("#addCorrespondenceRecord").click(function(){function a(){$("#addCorrespondenceRecordForm").modal("hide");$.ReportTranslate.ReloadReportCorrespondenceMeta()}var b=$("#correspondenceCustomer").find("input:radio:checked").val();b?(b={employee:$.ReportTranslate.getEmployee(),es2c:b,year:$("#correspondence-year").data("DateTimePicker").date().year(),month:$("#correspondence-month").val(),
SiteID:$("#correspondenceSite").find("input:radio:checked").val(),offset:$("#addCorrespondenceRecordOffset").val()},$.post(BaseUrl+"reports/correspondence/save",b,a,"json")):alert("\u041d\u0435 \u0432\u044b\u0431\u0440\u0430\u043d \u043a\u043b\u0438\u0435\u043d\u0442!")});$("#submit-report-salary").click(function(){var a={employee:$.ReportTranslate.getEmployee(),year:$("#salary-year").data("DateTimePicker").date().year(),month:$("#salary-month").val()};$.post(BaseUrl+"reports/salary/close",a,function(a){a.status?
alert("\u0414\u0430\u043d\u043d\u044b\u0435 \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u043e\u0442\u043f\u0440\u0430\u0432\u043b\u0435\u043d\u044b \u0432 \u0441\u0432\u043e\u0434\u043d\u0443\u044e \u0442\u0430\u0431\u043b\u0438\u0446\u0443"):alert("\u041e\u0448\u0438\u0431\u043a\u0430: "+a.message)},"json")});$(document).on("click","#ReportIndividualSalary_data>tbody>tr>td[type]>div",function(){if(0==$.ReportTranslate.getEmployee()){var a=$(this).parent().hasClass("decimal")?"decimal":"number";b($(this).parent(),
$(this).html(),a)}});$(document).on("focusout","#ReportIndividualSalary_data td[type]>input",function(){function a(a){d.html($("<div/>",{text:a}))}function b(c){c.status?(a(g),$.ReportTranslate.RefreshReportSalaryDataSummary()):(a(f),alert("\u041e\u0448\u0438\u0431\u043a\u0430: "+c.message))}var d=$(this).parent(),e=d.hasClass("decimal"),f=e?parseFloat($(this).attr("last-value")||0):parseInt($(this).attr("last-value")||0),g=e?parseFloat($(this).val()||0):parseInt($(this).val()||0);if(f!=g){var e=
$("#salary-year").data("DateTimePicker").date().year(),k=$("#salary-month").val(),l=d.attr("type"),e={idEmployeeSite:d.closest("tr").attr("id-employee-site"),year:e,month:k,type:l,value:g};$.post(BaseUrl+"reports/salary/save",e,b,"json")}else a(f)})},InitDynamicData:function(){},InitTemplate:function(){$("#reportIndividualDailyTemplate").template("reportIndividualDailyTemplate");$("#reportIndividualMailingTemplate").template("reportIndividualMailingTemplate");$("#reportIndividualSalaryTemplate").template("reportIndividualSalaryTemplate");
$("#reportIndividualCorrespondenceTemplate").template("reportIndividualCorrespondenceTemplate");$("#reportIndividualDaily_fixedWrapBody_Template").template("reportIndividualDaily_fixedWrapBody_Template");$("#reportIndividualDaily_total_Template").template("reportIndividualDaily_total_Template");$("#workSitesTemplate").template("workSitesTemplate");$("#correspondenceCustomerTemplate").template("correspondenceCustomerTemplate");$("#reportApprovedSalaryTemplate").template("reportApprovedSalaryTemplate")},
RefreshReportDailyDate:function(b){var a=$("#daily-year"),c=$("#daily-month"),d=$("#daily-day");b=moment([b||a.data("DateTimePicker").date().year(),c.val()]).endOf("month").date();d.empty();d.append($("<option/>",{value:0,text:"\u0437\u0430 \u043c\u0435\u0441\u044f\u0446"}));for(a=1;a<=b;a++)d.append($("<option/>",{value:a,text:a}));c.val()==moment().month()&&d.find("[value='"+moment().date()+"']").attr("selected","selected");$.ReportTranslate.ReloadReportDailyData()},ReloadReportDailyMeta:function(b){b=
void 0===b?!1:b;$("#ReportIndividualDaily_data").empty();$("#ReportIndividualDaily_fixedWrapBody").find(">tbody").empty();$("#ReportIndividualDaily_total").find(">tbody").empty();var a={year:$("#daily-year").data("DateTimePicker").date().year(),month:$("#daily-month").val(),day:$("#daily-day").val(),employee:$.ReportTranslate.getEmployee()};$.post(BaseUrl+"reports/daily/meta",a,function(a){a.status&&($.tmpl("reportIndividualDailyTemplate",a.records).appendTo("#ReportIndividualDaily_data"),$.tmpl("reportIndividualDaily_fixedWrapBody_Template",
a.records.customers).appendTo("#ReportIndividualDaily_fixedWrapBody>tbody"),$.tmpl("reportIndividualDaily_total_Template",a.records.customers).appendTo("#ReportIndividualDaily_total>tbody"),b||$.ReportTranslate.RefreshReportDailyDate())},"json")},ReloadReportDailyData:function(){var b={employee:$.ReportTranslate.getEmployee(),year:$("#daily-year").data("DateTimePicker").date().year(),month:$("#daily-month").val(),day:$("#daily-day").val()},a=0==$.ReportTranslate.getEmployee()&&0<b.day&&b.month==moment().month();
$.post(BaseUrl+"reports/daily/data",b,function(b){function d(a,b,c,d,l,h,m){b?a.addClass("is-edit"):a.removeClass("is-edit");a.addClass(c).attr("id-cross",d).attr("id-customer",l).attr("id-employee-site",h).find("div").html(m||0)}$("#ReportIndividualDaily_data").find(".mail div, .chat div").html(0);$.each(b.records,function(b,c){var g=c.CustomerID+"_"+c.EmployeeSiteID;d($("#mail_"+g),a,"mail",c.es2cID,c.CustomerID,c.EmployeeSiteID,c.emails);d($("#chat_"+g),a,"chat",c.es2cID,c.CustomerID,c.EmployeeSiteID,
c.chat)});$.ReportTranslate.RefreshReportDailyDataSummary()},"json")},RefreshReportDailyDataSummary:function(){var b=$("#ReportIndividualDaily_data"),a=$("#ReportIndividualDaily_total"),c=$("#ReportIndividualDaily_fixedWrapBody");$(b).find("tfoot td div").html(0);$(a).find("tbody td, tfoot td").html(0);$(c).find("tbody td, tfoot td").html(0);$(b).find("tbody .mail").each(function(){var a=$(this).attr("id-employee-site"),a=$("#foot_mail_"+a);a.html(parseFloat(a.html())+parseFloat($(this).find("div").html()));
a=$(this).attr("id-customer");a=$("#rid_total_"+a).find("td:eq(0)");a.html(parseFloat(a.html())+parseFloat($(this).find("div").html()))});$(b).find("tbody .chat").each(function(){var a=$(this).attr("id-employee-site"),a=$("#foot_chat_"+a);a.html(parseFloat(a.html())+parseFloat($(this).find("div").html()));var a=$(this).attr("id-customer"),b=$("#rid_total_"+a),c=b.find("td:eq(0)"),d=b.find("td:eq(1)"),b=b.find("td:eq(2)");d.html(parseFloat(d.html())+parseFloat($(this).find("div").html()));b.html(parseFloat(d.html())+
parseFloat(c.html()));$("#rid_slide_total_"+a).find("td").html(b.html())});var b=$(a).find("tfoot>tr"),d=b.find("td:eq(0)"),e=b.find("td:eq(1)"),f=b.find("td:eq(2)");$(a).find("tbody>tr").each(function(){d.html(parseFloat(d.html())+parseFloat($(this).find("td:eq(0)").html()));e.html(parseFloat(e.html())+parseFloat($(this).find("td:eq(1)").html()));f.html(parseFloat(f.html())+parseFloat($(this).find("td:eq(2)").html()))});$(c).find("tfoot>tr>td").html(f.html())},ReloadReportMailing:function(){var b=
$("#mailingSite").find("ul");b.empty();$.post(BaseUrl+"reports/sites",{employee:$.ReportTranslate.getEmployee()},function(a){a.status?($.tmpl("workSitesTemplate",a.records).appendTo(b),b.find("input:first").click()):alert("\u041e\u0448\u0438\u0431\u043a\u0430 \u043f\u043e\u043b\u0443\u0447\u0435\u043d\u0438\u044f \u0441\u043f\u0438\u0441\u043a\u0430 \u0441\u0430\u0439\u0442\u043e\u0432")},"json")},ReloadReportMailingMeta:function(b){b=b||$("#mailingSite").find("input:radio:checked").val();$("#ReportIndividualMailing_data").empty();
b={year:$("#mailing-year").data("DateTimePicker").date().year(),month:$("#mailing-month").val(),employee:$.ReportTranslate.getEmployee(),SiteID:b};$.post(BaseUrl+"reports/mailing/meta",b,function(a){if(a.status){var b=$("#mailing-year").data("DateTimePicker").date().year(),d=$("#mailing-month").val(),b=moment([b,d]).endOf("month").date();a.records.days=[];for(d=1;d<=b;d++)a.records.days.push(d);$.tmpl("reportIndividualMailingTemplate",a.records).appendTo("#ReportIndividualMailing_data");$.ReportTranslate.ReloadReportMailingData()}},
"json")},ReloadReportMailingData:function(){var b={employee:$.ReportTranslate.getEmployee(),year:$("#mailing-year").data("DateTimePicker").date().year(),month:$("#mailing-month").val(),SiteID:$("#mailingSite").find("input:radio:checked").val()};$.post(BaseUrl+"reports/mailing/data",b,function(a){a.status?($.each(a.records.days,function(a,b){var e=b.EmployeeSiteCustomerID,f=moment(b.date).date();$("#ReportIndividualMailing_data").find('tbody>tr[id-cross="'+e+'"]>td[day="'+f+'"]>div').html(b.value)}),
$.each(a.records.info,function(a,b){var e=b.EmployeeSiteCustomerID,f=b["id-info"],g=b["age-info"];0<f&&$("#ReportIndividualMailing_data").find('tbody>tr[id-cross="'+e+'"]>td[day="101"]>div').html(f);$("#ReportIndividualMailing_data").find('tbody>tr[id-cross="'+e+'"]>td[day="102"]>div').html(g)})):alert("\u041e\u0448\u0438\u0431\u043a\u0430 \u043f\u043e\u043b\u0443\u0447\u0435\u043d\u0438\u044f \u0434\u0430\u043d\u043d\u044b\u0445")},"json")},ReloadReportCorrespondence:function(){var b=$("#correspondenceSite").find("ul");
b.empty();$.post(BaseUrl+"reports/sites",{employee:$.ReportTranslate.getEmployee()},function(a){a.status?($.tmpl("workSitesTemplate",a.records).appendTo(b),b.find("input:first").click()):alert("\u041e\u0448\u0438\u0431\u043a\u0430 \u043f\u043e\u043b\u0443\u0447\u0435\u043d\u0438\u044f \u0441\u043f\u0438\u0441\u043a\u0430 \u0441\u0430\u0439\u0442\u043e\u0432")},"json")},ReloadReportCorrespondenceCustomers:function(){var b={employee:$.ReportTranslate.getEmployee(),SiteID:$("#correspondenceSite").find("input:radio:checked").val()},
a=$("#correspondenceCustomer"),c=a.find("ul");c.empty();a.find(".label-placement-wrap button").html("\u0412\u044b\u0431\u0440\u0430\u0442\u044c");$.post(BaseUrl+"reports/correspondence/customers",b,function(a){a.status?($.tmpl("correspondenceCustomerTemplate",a.records).appendTo(c),c.find("input:first").click()):alert("\u041e\u0448\u0438\u0431\u043a\u0430 \u043f\u043e\u043b\u0443\u0447\u0435\u043d\u0438\u044f \u0434\u0430\u043d\u043d\u044b\u0445")},"json")},ReloadReportCorrespondenceMeta:function(b){b=
b||$("#correspondenceSite").find("input:radio:checked").val();$("#ReportIndividualCorrespondence_data").empty();b={employee:$.ReportTranslate.getEmployee(),year:$("#correspondence-year").data("DateTimePicker").date().year(),month:$("#correspondence-month").val(),SiteID:b};$.post(BaseUrl+"reports/correspondence/meta",b,function(a){if(a.status){var b=$("#correspondence-year").data("DateTimePicker").date().year(),d=$("#correspondence-month").val(),b=moment([b,d]).endOf("month").date();a.records.days=
[];for(d=1;d<=b;d++)a.records.days.push(d);$.tmpl("reportIndividualCorrespondenceTemplate",a.records).appendTo("#ReportIndividualCorrespondence_data");$.ReportTranslate.ReloadReportCorrespondenceData();$.ReportTranslate.ReloadReportCorrespondenceCustomers()}},"json")},ReloadReportCorrespondenceData:function(){var b={employee:$.ReportTranslate.getEmployee(),year:$("#correspondence-year").data("DateTimePicker").date().year(),month:$("#correspondence-month").val(),SiteID:$("#correspondenceSite").find("input:radio:checked").val()};
$.post(BaseUrl+"reports/correspondence/data",b,function(a){a.status?$.each(a.records,function(a,b){var e=b.CorrespondenceInfoID,f=moment(b.date).date();$("#ReportIndividualCorrespondence_data").find('tbody>tr[id-record="'+e+'"]>td[day="'+f+'"]>div').html(b.value)}):alert("\u041e\u0448\u0438\u0431\u043a\u0430 \u043f\u043e\u043b\u0443\u0447\u0435\u043d\u0438\u044f \u0434\u0430\u043d\u043d\u044b\u0445")},"json")},ReloadReportSalary:function(){var b=$("#ReportIndividualSalary_data").find(">tbody");b.empty();
var a={employee:$.ReportTranslate.getEmployee(),year:$("#salary-year").data("DateTimePicker").date().year(),month:$("#salary-month").val()};$.post(BaseUrl+"reports/salary/data",a,function(a){a.status?($.tmpl("reportIndividualSalaryTemplate",a.records).appendTo(b),$.ReportTranslate.RefreshReportSalaryDataSummary()):alert("\u041e\u0448\u0438\u0431\u043a\u0430 \u043f\u043e\u043b\u0443\u0447\u0435\u043d\u0438\u044f \u0434\u0430\u043d\u043d\u044b\u0445")},"json")},RefreshReportSalaryDataSummary:function(){var b=
$("#ReportIndividualSalary_data");$(b).find("tr").find("td:last").html(0);$(b).find("tbody>tr").each(function(){var a=parseFloat($(this).find("td:eq(2) div").html())||0,b=parseFloat($(this).find("td:eq(4) div").html())||0,e=parseFloat($(this).find("td:eq(6) div").html())||0,f=parseFloat($(this).find("td:eq(8) div").html())||0;$(this).find("td:last").html((a+b+e+f).toFixed(2))});var a=$(b).find("tfoot td:last");$(b).find("tbody>tr").find("td:last").each(function(){var b=parseFloat($(this).html())||
0,d=parseFloat(a.html())||0;a.html((d+b).toFixed(2))})},ReloadReportApprovedSalary:function(b){var a=$("#ReportApprovedSalary_data").find(">tbody");a.empty();b={year:b||$("#approved-salary-year").data("DateTimePicker").date().year(),month:$("#approved-salary-month").val()};$.post(BaseUrl+"reports/approved/salary/data",b,function(b){if(b.status){$.tmpl("reportApprovedSalaryTemplate",b.records).appendTo(a);b=$("#ReportApprovedSalary_data").find(">tfoot");var d=b.find("strong:eq(0)"),e=b.find("strong:eq(1)"),
f=b.find("strong:eq(2)");b.find("strong").html(0);a.find("tr").each(function(){var a=1==parseFloat($(this).attr("paid")),b=parseFloat($(this).find("td:eq(1)").html());d.html((parseFloat(d.html())+b).toFixed(2));a?e.html((parseFloat(e.html())+b).toFixed(2)):f.html((parseFloat(f.html())+b).toFixed(2))})}else alert("\u041e\u0448\u0438\u0431\u043a\u0430 \u043f\u043e\u043b\u0443\u0447\u0435\u043d\u0438\u044f \u0434\u0430\u043d\u043d\u044b\u0445")},"json")},employee:0,setEmployee:function(b){this.employee=
b},getEmployee:function(){return this.employee}};$.ReportTranslate.Init()});