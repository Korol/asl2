$(document).ready(function(){$.AssolTraining={Init:function(){this.InitActions();this.InitDynamicData();this.InitTemplate()},InitActions:function(){$(document).on("click",".action-training-add",function(){window.location.href=BaseUrl+"training/"+$.AssolTraining.GetParent()+"/add/file"});$(document).on("click",".action-training-edit",function(a){window.location.href=BaseUrl+"training/"+$.AssolTraining.GetParent()+"/edit/"+$(a.target).closest("[record]").attr("record")});$(document).on("click",".action-training-open",
function(a){window.location.href=BaseUrl+"training/"+$.AssolTraining.GetParent()+"/show/"+$(a.target).closest("[record]").attr("record")});$(document).on("click",".action-folder-open",function(a){$.AssolTraining.SetParent($(a.target).closest("[record]").attr("record"));$.AssolTraining.ReloadTrainingList()});$(document).on("click",".action-folder-remove",function(a){confirmRemove(function(){$.AssolTraining.RemoveTraining($(a.target).closest("[record]").attr("record"))})});$(document).on("click",".action-document-remove",
function(a){confirmRemove(function(){$.AssolTraining.RemoveTraining($(a.target).closest("[record]").attr("record"))})});$(document).on("click",".action-file-remove",function(a){confirmRemove(function(){$.AssolTraining.RemoveFile($(a.target).closest("[record]").attr("record"))})})},InitDynamicData:function(){this.ReloadTrainingList()},InitTemplate:function(){$("#trainingTemplate").template("trainingTemplate")},ReloadTrainingList:function(a){this.ReloadData("#training","trainingTemplate",a)},ReloadData:function(a,
d,c){$(a).html("\u0417\u0430\u0433\u0440\u0443\u0437\u043a\u0430 \u0434\u0430\u043d\u043d\u044b\u0445...");c=c||{Parent:$.AssolTraining.GetParent()};$.post(BaseUrl+"training/data",c,function(b){b.status?b.records&&($(a).empty(),$.tmpl(d,b.records).appendTo(a)):showErrorAlert(b.message)},"json")},RemoveTraining:function(a){$.post(BaseUrl+"training/remove",{id:a},function(a){a.status?$.AssolTraining.ReloadTrainingList():showErrorAlert(a.message)},"json")},RemoveFile:function(a){$.post(BaseUrl+"training/delete_file/"+
a,{id:a},function(a){a.status?$.AssolTraining.ReloadTrainingList():showErrorAlert(a.message)},"json")},parent:UrlParent,SetParent:function(a){this.parent=a},GetParent:function(){return this.parent}};$.AssolTraining.Init()});