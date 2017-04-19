$(document).ready(function(){"use strict";function e(e){$("#alertErrorMessage").text(e),$("#alertError").slideDown()}function t(){$("#alertError").hide()}$.AssolCalendar={Init:function(){this.InitActions(),this.InitDynamicData(),this.InitTemplate(),this.InitCalendar()},InitActions:function(){$("#btnAddCalendarEvent").click(this.SaveCalendarEvent),$("#btnShowDayReport").click(this.ShowReportDay),$("#btnSaveDayReport").click(this.SaveReportDay),$("#AllDayCheckbox").click(this.UpdateEventTimeFormat)},InitDynamicData:function(){},InitTemplate:function(){$("#reportTemplate").template("reportTemplate")},InitCalendar:function(){$("#calendar").fullCalendar({header:{left:"prev,next today",center:"title",right:"month,agendaWeek,agendaDay"},select:function(e,t){$.AssolCalendar.DialogCalendarEvent(e,t)},eventClick:function(e,t,a){$.AssolCalendar.DialogCalendarEvent(e.start,e.end,e.id,e.title,e.description,e.remind,!0,jQuery.inArray("action-birthday",e.className)>-1)},selectable:!0,columnFormat:"dddd",eventLimit:!0,slotLabelFormat:"H:mm",events:{url:BaseUrl+"calendar/data",beforeSend:function(){t()},success:function(t){t.error&&e("Ошибка загрузки событий: "+t.message)},error:function(){e("Ошибка загрузки событий!")}}})},DoneEvent:function(e){bootbox.confirm("Поставить метку выполнено для события?",function(t){function a(){$("#ShowCalendarEvent").modal("hide"),$("#calendar").fullCalendar("refetchEvents")}t&&$.post(BaseUrl+"calendar/done",{id:e},a,"json")})},SaveCalendarEvent:function(){function e(e){e.status?($("#AddCalendarEvent").modal("hide"),$("#calendar").fullCalendar("refetchEvents")):alert(e.message)}t();var a={id:$("#AddCalendarEventID").val(),title:$("#AddCalendarEventTitle").val(),remind:$("#Remind").find("input:checked").val(),description:$("#AddCalendarEventDescription").val()};$("#AllDayCheckbox").prop("checked")?(a.start=$("#event-start").data("DateTimePicker").date().format("YYYY-MM-DD")+" 00:00:00",a.end=$("#event-end").data("DateTimePicker").date().format("YYYY-MM-DD")+" 23:59:59"):(a.start=$("#event-start").data("DateTimePicker").date().format("YYYY-MM-DD HH:mm"),a.end=$("#event-end").data("DateTimePicker").date().format("YYYY-MM-DD HH:mm")),$.post(BaseUrl+"calendar/save",a,e,"json")},ShowReportDay:function(){$("#btnShowDayReport").hide(),$("#formDayReport").slideDown("slow",function(){function e(e){$("#reportItems").empty(),$.tmpl("reportTemplate",e).appendTo("#reportItems"),$("html, body").animate({scrollTop:$("#formDayReport").offset().top},1e3)}$("#reportItems").html("<tr><td>Загрузка данных...</td></tr>"),$.getJSON(BaseUrl+"calendar/data",e)})},SaveReportDay:function(){function e(){$("#formDayReport").slideUp("slow",function(){$("#btnShowDayReport").show()})}var t={};$("#formDayReport").find("input").each(function(){t[$(this).attr("id-event")]=$(this).val()}),$.post(BaseUrl+"calendar/report",{data:t},e,"json")},DialogCalendarEvent:function(e,t,a,n,r,o,d,l){a?t=t||e:t?t.subtract(1,"seconds"):t=e;var i=$.AssolCalendar.IsAllDay(e,t);if(o=o||0,d){var s=i?"DD.MM.YYYY":"DD.MM.YYYY HH:mm";$("#ShowCalendarEventLabel").html(n),$("#showTaskDescription").html(r),$("#showEventStart").html(e.format(s)),$("#showEventEnd").html(t.format(s)),$("#showRemind").html($('label[for="Remind_'+o+'"]').html()),$("#showRemind").closest("tr").css("display",o>0?"table-row":"none"),$("#btnEditEvent").off("click.edit-event").on("click.edit-event",function(){$("#ShowCalendarEvent").modal("hide"),$.AssolCalendar.DialogCalendarEvent(e,t,a,n,r,o)}),$("#btnDoneEvent").off("click.done-event").on("click.done-event",function(){$.AssolCalendar.DoneEvent(a)}),$("#ShowCalendarEvent").modal("show").find(".change-task-description-wrap, .save-edit-wrap").css("display",l?"none":"block")}else $("#AddCalendarEventID").val(a),$("#AddCalendarEventLabel").html(a?"РЕДАКТИРОВАНИЕ СОБЫТИЯ":"НОВОЕ СОБЫТИЕ"),$("#AddCalendarEventTitle").val(n),$("#AddCalendarEventDescription").val(r),$("#event-start").data("DateTimePicker").date(e),$("#event-end").data("DateTimePicker").date(t),$("#AllDayCheckbox").prop("checked",i),$("#Remind_"+o).click(),$.AssolCalendar.UpdateEventTimeFormat(),$("#AddCalendarEvent").modal("show")},IsAllDay:function(e,t){var a=$.AssolCalendar.IsStartDay(e),n=$.AssolCalendar.IsEndDay(t)||$.AssolCalendar.IsStartDay(t);return a&&n},IsStartDay:function(e){return!(e.hour()||e.minute()||e.seconds())},IsEndDay:function(e){return 23==e.hour()&&59==e.minute()&&59==e.seconds()},UpdateEventTimeFormat:function(){var e=$("#AllDayCheckbox").prop("checked")?"DD.MM.YYYY":"DD.MM.YYYY HH:mm",t=$("#event-start").data("DateTimePicker"),a=$("#event-end").data("DateTimePicker");if($("#AllDayCheckbox").prop("checked"))t.format(e),a.format(e);else{var n=moment(t.date()),r=moment(a.date());n.isSame(r)&&$.AssolCalendar.IsStartDay(n)&&a.date(r.hours(23).minute(59).seconds(59)),t.format(e),a.format(e)}}},$.AssolCalendar.Init()});