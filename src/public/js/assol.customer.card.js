$(document).ready(function(){function g(a){var b={};$.each(a,function(a,c){var e=$("#"+c),d=e.is("div")?$(e).find("input:checked").val():$(e).val(),g=CustomerRecord[c];$.isBlank(d)&&$.isBlank(g)||d==g||(b[c]=isDateField(e)?toServerDate(d):d,CustomerRecord[c]=d)});return b}function c(a){$("#alertErrorMessage").text(a);$("#alertError").slideDown()}function e(a){$("#alertSuccessMessage").text(a);$("#alertSuccess").slideDown()}function d(){$("#alertError").hide();$("#alertSuccess").hide()}$.CustomerCard=
{GetCustomerUpdateUrl:function(){return BaseUrl+"customer/"+CustomerID+"/update"},Init:function(){this.InitActions();this.InitDynamicData();this.InitTemplate()},InitActions:function(){$("#SavePersonalData").click(this.SavePersonalData);$("#SaveSelfDescription").click(this.SaveSelfDescription);$("#QuestionDescription").click(this.QuestionDescription);$("#SaveAdditionally").click(this.SaveAdditionally);$("#SaveRemove").click(this.SaveRemove);$("#SaveReservationContact").click(this.SaveReservationContact);
$("#SaveSites").click(this.SaveSites);$("#SaveVideo").click(this.SaveVideoSites);$("#SavePhoto").click(this.SavePhoto);$("#SavePhotoAndVideo").click(this.SavePhotoAndVideo);$("#SaveSiteSelfDescription").click(this.SaveSiteSelfDescription);$("#SaveSiteSelfDescription1").click(this.SaveSiteSelfDescription1);$("#SaveSiteSelfDescription2").click(this.SaveSiteSelfDescription2);$("#SaveSiteSelfDescription3").click(this.SaveSiteSelfDescription3);$("#CustomerRemove").click(this.CustomerRemove);$("#CustomerMarkRemove").click(this.CustomerMarkRemove);
$("#CustomerRestore").click(this.CustomerRestore);$("#SaveVerificationLink").click(function(){var a=$("#VerificationLink").val();a&&$.CustomerCard.SaveVideoLink(a,0)});$("#SaveAmateurLink").click(function(){var a=$("#AmateurLink").val();a&&$.CustomerCard.SaveVideoLink(a,1)});$(document).on("click",".action-remove-passport",function(a){confirmRemove(function(){$.CustomerCard.RemovePassportRecord($(a.target).attr("record"))})});$(document).on("click",".action-remove-agreement",function(a){confirmRemove(function(){$.CustomerCard.RemoveAgreementRecord($(a.target).attr("record"))})});
$(document).on("click",".action-remove-question-photo",function(a){confirmRemove(function(){$.CustomerCard.RemoveQuestionPhotoRecord($(a.target).attr("record"))})});$(document).on("click",".action-remove-site",function(a){var b=$(a.target).closest(".work-sites-block").attr("record"),f=$(a.target).attr("record");confirmRemove(function(){$.CustomerCard.RemoveSiteRecord(b,f)})});$(document).on("click",".action-append-video-site",function(a){var b=$(a.target).closest(".work-sites-block").attr("video-site"),
f=$(a.target).closest(".form-group").find("input").val();a=$(a.target).closest("[video-type]").attr("video-type");$.CustomerCard.SaveVideoSiteLink(b,f,a)});$(document).on("click",".action-remove-question",function(a){confirmRemove(function(){$.CustomerCard.RemoveQuestionRecord($(a.target).closest("[record]").attr("record"))})});$(document).on("click",".action-save-answer",function(a){$.CustomerCard.SaveQuestionAnswer($(a.target).closest("button").attr("record"))});$(document).on("click",".action-save-language",
function(a){$.CustomerCard.SaveLanguageRecord($(a.target).closest("button").attr("record"))});$(document).on("click",".action-remove-language",function(a){confirmRemove(function(){$.CustomerCard.RemoveLanguageRecord($(a.target).closest("button").attr("record"))})});$(document).on("click",".action-save-children",function(a){$.CustomerCard.SaveChildrenRecord($(a.target).closest("button").attr("record"))});$(document).on("click",".action-remove-children",function(a){confirmRemove(function(){$.CustomerCard.RemoveChildrenRecord($(a.target).closest("button").attr("record"))})});
$(document).on("click",".action-remove-story",function(a){confirmRemove(function(){$.CustomerCard.RemoveStoryRecord($(a.target).closest("button").attr("record"))})});$(document).on("click",".action-save-email",function(a){$.CustomerCard.SaveEmailRecord($(a.target).closest("button").attr("record"))});$(document).on("click",".action-remove-email",function(a){confirmRemove(function(){$.CustomerCard.RemoveEmailRecord($(a.target).closest("button").attr("record"))})});$(document).on("click",".action-remove-video",
function(a){confirmRemove(function(){$.CustomerCard.RemoveVideoRecord($(a.target).closest("button").attr("record"))})});$(document).on("click",".action-remove-video-site",function(a){var b=$(a.target).closest(".work-sites-block").attr("id-site"),f=$(a.target).closest(".work-sites-block").attr("video-site");confirmRemove(function(){$.CustomerCard.RemoveVideoSiteRecord(b,f)})});$(document).on("click",".action-remove-video-site-link",function(a){var b=$(a.target).closest(".work-sites-block").attr("video-site");
a=$(a.target).closest("button");var f=a.attr("video-link"),c=a.attr("video-type");confirmRemove(function(){$.CustomerCard.RemoveVideoSiteLinkRecord(b,f,c)})});$(document).on("click",".action-remove-album",function(a){confirmRemove(function(){$.CustomerCard.RemoveAlbumRecord($(a.target).closest("div[record]").attr("record"))})});$(document).on("click",".image-remove-btn",function(a){confirmRemove(function(){$.CustomerCard.RemoveImageAlbumRecord($(a.target).closest("div[id-cross]").attr("id-cross"))})});
$(document).on("change","#addClientAvatar",function(a){$("#AvatarForm").submit()});$("#AvatarForm").ajaxForm(function(a){a.status?$.CustomerCard.RefreshAvatar(a.id,a.FileName):c(a.message)});$.each(EmployeeRights,function(a,b){$("#employeeAccess_"+b.EmployeeID).click()});$("#SaveEmployeeAccess").click(function(){var a=[];$("#employeeAccess").find("input:checked").each(function(){a.push($(this).val())});$.post(BaseUrl+"/customer/"+CustomerID+"/rights",{Customer:CustomerID,Employees:a},function(a){a.status?
e("\u041f\u0440\u0430\u0432\u0430 \u043f\u043e\u043b\u044c\u0437\u043e\u0432\u0430\u0442\u0435\u043b\u044f \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d\u044b"):c(a.message)},"json")});$('a[href="#Questions"]').on("shown.bs.tab",function(){$.each($("textarea[data-autoresize]"),function(){var a=this.offsetHeight-this.clientHeight,b=function(b){$(b).css("height","auto").css("height",b.scrollHeight+a)};b($(this));$(this).on("keyup input",function(){b(this)}).removeAttr("data-autoresize")})});
$('a[href="#PhotoAndVideo"]').on("shown.bs.tab",function(){$.CustomerCard.ReloadVideoVerificationList();$.CustomerCard.ReloadVideoAmateurList()});$('a[href="#Photo"]').on("shown.bs.tab",function(){$.CustomerCard.ReloadAlbumList()});$('a[href="#Video"]').on("shown.bs.tab",function(){$.CustomerCard.ReloadVideoSiteList()});$('a[href="#Story"]').on("shown.bs.tab",function(){$.CustomerCard.ReloadStoryList()});$('a[href="#Sites"]').on("shown.bs.tab",function(){$.CustomerCard.ReloadSiteList()})},InitDynamicData:function(){this.ReloadPassportList();
this.ReloadAgreementList();this.ReloadQuestionPhotoList();this.ReloadLanguageList();this.ReloadChildrenList();IsLoveStory||this.ReloadEmailList()},InitTemplate:function(){$("#agreementTemplate").template("agreementTemplate");$("#albumTemplate").template("albumTemplate");$("#emailTemplate").template("emailTemplate");$("#passportTemplate").template("passportTemplate");$("#questionTemplate").template("questionTemplate");$("#questionPhotoTemplate").template("questionPhotoTemplate");$("#videoTemplate").template("videoTemplate");
$("#languageTemplate").template("languageTemplate");$("#childrenTemplate").template("childrenTemplate");$("#storyTemplate").template("storyTemplate");$("#siteTemplate").template("siteTemplate");$("#videoSiteTemplate").template("videoSiteTemplate");$("#videoSiteLinkTemplate").template("videoSiteLinkTemplate")},CustomerRemove:function(){confirmRemove(function(){d();$.post(BaseUrl+"customer/"+CustomerID+"/remove",{IsFull:!0},function(a){a.status?(alert("\u0417\u0430\u043f\u0438\u0441\u044c \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0443\u0434\u0430\u043b\u0435\u043d\u0430"),
window.location=BaseUrl+"customer"):c(a.message)},"json")},"\u0412\u044b \u0434\u0435\u0439\u0441\u0442\u0432\u0438\u0442\u0435\u043b\u044c\u043d\u043e \u0445\u043e\u0442\u0438\u0442\u0435 \u0411\u0415\u0417\u0412\u041e\u0417\u0412\u0420\u0410\u0422\u041d\u041e \u0443\u0434\u0430\u043b\u0438\u0442\u044c \u0437\u0430\u043f\u0438\u0441\u044c?")},CustomerMarkRemove:function(){confirmRemove(function(){d();$.post(BaseUrl+"customer/"+CustomerID+"/remove",{},function(a){a.status?e("\u0417\u0430\u043f\u0438\u0441\u044c \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0443\u0434\u0430\u043b\u0435\u043d\u0430"):
c(a.message)},"json")})},CustomerRestore:function(){d();$.post(BaseUrl+"customer/"+CustomerID+"/restore",{},function(a){a.status?e("\u0417\u0430\u043f\u0438\u0441\u044c \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0432\u043e\u0441\u0441\u0442\u0430\u043d\u043e\u0432\u043b\u0435\u043d\u0430"):c(a.message)},"json")},RefreshAvatar:function(a,b){var f=0<a?BaseUrl+"thumb/?src=/files/images/"+b:BaseUrl+"public/img/avatar-example.png";$("#AvatarBig").attr("src",f);$("#AvatarCard").attr("src",f)},ReloadAgreementList:function(){this.ReloadData("#agreement",
"agreement","agreementTemplate",!0)},ReloadAlbumList:function(){this.ReloadData("#album-list","album","albumTemplate")},ReloadLanguageList:function(){this.ReloadData("#languageList","language","languageTemplate",{ID:0,CustomerID:CustomerID,LanguageID:0,Level:0})},ReloadChildrenList:function(){this.ReloadData("#childrenList","children","childrenTemplate",{ID:0,CustomerID:CustomerID,SexID:0,FIO:"",DOB:""})},ReloadPassportList:function(){this.ReloadData("#passport","passport","passportTemplate",{ID:0})},
ReloadQuestionPhotoList:function(){this.ReloadData("#questionPhoto","question/photo","questionPhotoTemplate",{ID:0})},ReloadQuestionList:function(){this.ReloadData("#QuestionList","question","questionTemplate")},ReloadVideoVerificationList:function(){this.ReloadData("#VideoVerificationList","video_0","videoTemplate")},ReloadVideoAmateurList:function(){this.ReloadData("#VideoAmateurList","video_1","videoTemplate")},ReloadVideoSiteVerificationList:function(a){this.ReloadData("#VideoSiteVerificationList_"+
a,"video/site/"+a+"/video_0","videoSiteLinkTemplate")},ReloadVideoSiteAmateurList:function(a){this.ReloadData("#VideoSiteAmateurList_"+a,"video/site/"+a+"/video_1","videoSiteLinkTemplate")},ReloadVideoSiteMailList:function(a){this.ReloadData("#VideoSiteMailList_"+a,"video/site/"+a+"/video_2","videoSiteLinkTemplate")},ReloadVideoSiteList:function(){this.ReloadData("#videoSiteList","video/site","videoSiteTemplate",null,function(){$(this).each(function(){$.CustomerCard.ReloadVideoSiteVerificationList(this.ID);
$.CustomerCard.ReloadVideoSiteAmateurList(this.ID);$.CustomerCard.ReloadVideoSiteMailList(this.ID)})})},ReloadEmailList:function(){this.ReloadData("#emailList","email","emailTemplate",{ID:0,CustomerID:CustomerID,Email:"",Note:""})},ReloadSiteList:function(){this.ReloadData("#siteList","site","siteTemplate")},ReloadStoryList:function(){this.ReloadData("#storyList","story","storyTemplate",{ID:0,CustomerID:CustomerID,SiteID:0,Date:"",Name:"",Note:"",Avatar:0},function(){$("#storyList").find('input[type="file"]').filestyle({input:!1,
buttonText:"",buttonName:"story-avatar",iconName:""});$("#storyList").find("form").ajaxForm(function(a){a.status?(e("\u0417\u0430\u043f\u0438\u0441\u044c \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d\u0430"),$.CustomerCard.ReloadStoryList()):c(a.message)});$(function(){$('[data-toggle="story-popover"]').popover()})})},ReloadData:function(a,b,f,d,e){$(a).html("\u0417\u0430\u0433\u0440\u0443\u0437\u043a\u0430 \u0434\u0430\u043d\u043d\u044b\u0445...");$.post(BaseUrl+
"customer/"+CustomerID+"/"+b+"/data",{},function(b){b.status?b.records&&($(a).empty(),$.tmpl(f,b.records).appendTo(a),d&&$.tmpl(f,d).appendTo(a),$("input:checked").change(),"function"===typeof e&&e.call(b.records)):c(b.message)},"json")},RemoveAgreementRecord:function(a){d();$.post(BaseUrl+"customer/"+CustomerID+"/agreement/"+a+"/remove",{},function(a){a.status?(e("\u0414\u043e\u043a\u0443\u043c\u0435\u043d\u0442 \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0443\u0434\u0430\u043b\u0435\u043d"),$.CustomerCard.ReloadAgreementList()):
c(a.message)},"json")},RemoveAlbumRecord:function(a){d();$.post(BaseUrl+"customer/"+CustomerID+"/album/"+a+"/remove",{},function(a){a.status?(e("\u0417\u0430\u043f\u0438\u0441\u044c \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0443\u0434\u0430\u043b\u0435\u043d\u0430"),$.CustomerCard.ReloadAlbumList()):c(a.message)},"json")},RemoveImageAlbumRecord:function(a){d();$.post(BaseUrl+"customer/"+CustomerID+"/album/cross/"+a+"/remove",{},function(a){a.status?(e("\u0417\u0430\u043f\u0438\u0441\u044c \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0443\u0434\u0430\u043b\u0435\u043d\u0430"),
$.CustomerCard.ReloadAlbumList()):c(a.message)},"json")},RemoveLanguageRecord:function(a){d();$.post(BaseUrl+"customer/"+CustomerID+"/language/"+a+"/remove",{},function(a){a.status?(e("\u042f\u0437\u044b\u043a \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0443\u0434\u0430\u043b\u0435\u043d"),$.CustomerCard.ReloadLanguageList()):c(a.message)},"json")},RemoveChildrenRecord:function(a){d();$.post(BaseUrl+"customer/"+CustomerID+"/children/"+a+"/remove",{},function(a){a.status?(e("\u0417\u0430\u043f\u0438\u0441\u044c \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0443\u0434\u0430\u043b\u0435\u043d\u0430"),
$.CustomerCard.ReloadChildrenList()):c(a.message)},"json")},RemoveEmailRecord:function(a){d();$.post(BaseUrl+"customer/"+CustomerID+"/email/"+a+"/remove",{},function(a){a.status?(e("\u0417\u0430\u043f\u0438\u0441\u044c \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0443\u0434\u0430\u043b\u0435\u043d\u0430"),$.CustomerCard.ReloadEmailList()):c(a.message)},"json")},RemoveStoryRecord:function(a){d();$.post(BaseUrl+"customer/"+CustomerID+"/story/"+a+"/remove",{},function(a){a.status?(e("\u0417\u0430\u043f\u0438\u0441\u044c \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0443\u0434\u0430\u043b\u0435\u043d\u0430"),
$.CustomerCard.ReloadStoryList()):c(a.message)},"json")},RemovePassportRecord:function(a){d();$.post(BaseUrl+"customer/"+CustomerID+"/passport/"+a+"/remove",{},function(a){a.status?(e("\u0414\u043e\u043a\u0443\u043c\u0435\u043d\u0442 \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0443\u0434\u0430\u043b\u0435\u043d"),$.CustomerCard.ReloadPassportList()):c(a.message)},"json")},RemoveQuestionPhotoRecord:function(a){d();$.post(BaseUrl+"customer/"+CustomerID+"/question/photo/"+a+"/remove",{},function(a){a.status?
(e("\u0418\u0437\u043e\u0431\u0440\u0430\u0436\u0435\u043d\u0438\u0435 \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0443\u0434\u0430\u043b\u0435\u043d\u043e"),$.CustomerCard.ReloadQuestionPhotoList()):c(a.message)},"json")},RemoveSiteRecord:function(a,b){d();$.post(BaseUrl+"customer/"+CustomerID+"/site/"+b+"/remove",{},function(b){b.status?(e("\u0417\u0430\u043f\u0438\u0441\u044c \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0443\u0434\u0430\u043b\u0435\u043d\u0430"),$.CustomerCard.ReloadSiteList(),
$("#WorkSite_"+a).click()):c(b.message)},"json")},RemoveVideoSiteRecord:function(a,b){d();$.post(BaseUrl+"customer/"+CustomerID+"/video/site/"+b+"/remove",{},function(b){b.status?(e("\u0417\u0430\u043f\u0438\u0441\u044c \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0443\u0434\u0430\u043b\u0435\u043d\u0430"),$.CustomerCard.ReloadVideoSiteList(),$("#VideoSite_"+a).click()):c(b.message)},"json")},RemoveVideoSiteLinkRecord:function(a,b,f){d();$.post(BaseUrl+"customer/"+CustomerID+"/video/site/link/"+b+
"/remove",{},function(b){if(b.status)switch(e("\u0417\u0430\u043f\u0438\u0441\u044c \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0443\u0434\u0430\u043b\u0435\u043d\u0430"),parseInt(f)){case 0:$.CustomerCard.ReloadVideoSiteVerificationList(a);break;case 1:$.CustomerCard.ReloadVideoSiteAmateurList(a);break;case 2:$.CustomerCard.ReloadVideoSiteMailList(a)}else c(b.message)},"json")},RemoveVideoRecord:function(a){d();$.post(BaseUrl+"customer/"+CustomerID+"/video/"+a+"/remove",{},function(a){a.status?
(e("\u0417\u0430\u043f\u0438\u0441\u044c \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0443\u0434\u0430\u043b\u0435\u043d\u0430"),$.CustomerCard.ReloadVideoAmateurList(),$.CustomerCard.ReloadVideoVerificationList()):c(a.message)},"json")},RemoveQuestionRecord:function(a){d();$.post(BaseUrl+"customer/"+CustomerID+"/question/"+a+"/remove",{},function(a){a.status?(e("\u0417\u0430\u043f\u0438\u0441\u044c \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0443\u0434\u0430\u043b\u0435\u043d\u0430"),$.CustomerCard.ReloadQuestionList()):
c(a.message)},"json")},SavePersonalData:function(){function a(a){a.status?e("\u041b\u0438\u0447\u043d\u044b\u0435 \u0434\u0430\u043d\u043d\u044b\u0435 \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d\u044b"):c(a.message)}d();var b=g("SName FName MName DOB DateRegister City Postcode Country Address Phone_1 Phone_2 Email Forming ProfessionOfDiploma CurrentWork Worship MaritalStatus PassportSeries PassportNumber Height Weight HairColor EyeColor BodyBuild BodyBuildID SizeFoot Smoking Alcohol FootSize FingerSize ClothingSize".split(" "));
$.isBlank(b)||$.post($.CustomerCard.GetCustomerUpdateUrl(),{data:b},a,"json")},SaveSelfDescription:function(){function a(a){a.status?e("\u0421\u0430\u043c\u043e\u043e\u043f\u0438\u0441\u0430\u043d\u0438\u0435 \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d\u043e"):c(a.message)}d();var b=g("Temper Interests WishesForManAgeMin WishesForManAgeMax WishesForManWeight WishesForManHeight WishesForManText WishesForManNationality".split(" "));$.isBlank(b)||$.post($.CustomerCard.GetCustomerUpdateUrl(),
{data:b},a,"json")},SaveSiteSelfDescription:function(){function a(a){a.status?e("\u0410\u043d\u043a\u0435\u0442\u0430 \u0437\u0430\u043a\u0440\u0435\u043f\u043b\u0435\u043d\u0430 \u0437\u0430 \u0432\u044b\u0431\u0440\u0430\u043d\u043d\u044b\u043c \u0441\u043e\u0442\u0440\u0443\u0434\u043d\u0438\u043a\u043e\u043c"):c(a.message)}d();var b=g("ssdCharacter ssdHobbies ssdWishingForPartner ssdPresentationLetter ssdMailingList1 ssdMailingList2 ssdMailingList3 ssdResponsibleStaff".split(" "));b.ssdStatus=
1;$("#SaveSiteSelfDescription").remove();$.isBlank(b)||$.post($.CustomerCard.GetCustomerUpdateUrl(),{data:b},a,"json")},SaveSiteSelfDescription1:function(){function a(a){a.status?e("\u0410\u043d\u043a\u0435\u0442\u0430 \u043e\u0442\u043f\u0440\u0430\u0432\u043b\u0435\u043d\u0430 \u043d\u0430 \u0443\u0442\u0432\u0435\u0440\u0436\u0434\u0435\u043d\u0438\u0435 \u0414\u0438\u0440\u0435\u043a\u0442\u043e\u0440\u0443"):c(a.message)}d();var b=g("ssdCharacter ssdHobbies ssdWishingForPartner ssdPresentationLetter ssdMailingList1 ssdMailingList2 ssdMailingList3 ssdResponsibleStaff".split(" "));
b.ssdStatus=2;$("#SaveSiteSelfDescription1").remove();$.isBlank(b)||$.post($.CustomerCard.GetCustomerUpdateUrl(),{data:b},a,"json")},SaveSiteSelfDescription2:function(){function a(a){a.status?e("\u0410\u043d\u043a\u0435\u0442\u0430 \u043f\u043e\u0434\u0442\u0432\u0435\u0440\u0436\u0434\u0435\u043d\u0430"):c(a.message)}d();var b=g("ssdCharacter ssdHobbies ssdWishingForPartner ssdPresentationLetter ssdMailingList1 ssdMailingList2 ssdMailingList3 ssdResponsibleStaff".split(" "));b.ssdStatus=3;$("#SaveSiteSelfDescription2").remove();
$.isBlank(b)||$.post($.CustomerCard.GetCustomerUpdateUrl(),{data:b},a,"json")},SaveSiteSelfDescription3:function(){function a(a){a.status?e("\u0410\u043d\u043a\u0435\u0442\u0430 \u043e\u0442\u043f\u0440\u0430\u0432\u043b\u0435\u043d\u0430 \u043d\u0430 \u0434\u043e\u0440\u0430\u0431\u043e\u0442\u043a\u0443 \u043e\u0442\u0432\u0435\u0442\u0441\u0442\u0432\u0435\u043d\u043d\u043e\u043c\u0443 \u0441\u043e\u0442\u0440\u0443\u0434\u043d\u0438\u043a\u0443"):c(a.message)}d();var b=$("#ssdRSComment").val();
(b=b.replace(/^\s+|\s+$/g,""))?(b=g("ssdCharacter ssdHobbies ssdWishingForPartner ssdPresentationLetter ssdMailingList1 ssdMailingList2 ssdMailingList3 ssdResponsibleStaff ssdRSComment".split(" ")),b.ssdStatus=1,$("#SaveSiteSelfDescription3").remove(),$("#SaveSiteSelfDescription2").remove(),$.isBlank(b)||$.post($.CustomerCard.GetCustomerUpdateUrl(),{data:b},a,"json")):c("\u0423\u043a\u0430\u0436\u0438\u0442\u0435 \u0432\u0430\u0448\u0438 \u0437\u0430\u043c\u0435\u0447\u0430\u043d\u0438\u044f \u0432 \u043f\u043e\u043b\u0435 \u00ab\u041a\u043e\u043c\u043c\u0435\u043d\u0442\u0430\u0440\u0438\u0439 \u043e\u0442\u0432\u0435\u0442\u0441\u0442\u0432\u0435\u043d\u043d\u043e\u043c\u0443\u00bb!")},
QuestionDescription:function(){function a(a){a.status?e("\u0412\u043e\u043f\u0440\u043e\u0441\u044b \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d\u044b"):c(a.message)}d();var b=g(["Question"]);$.isBlank(b)||$.post($.CustomerCard.GetCustomerUpdateUrl(),{data:b},a,"json")},SaveAdditionally:function(){function a(a){a.status?e("\u0414\u043e\u043f\u043e\u043b\u043d\u0438\u0442\u0435\u043b\u044c\u043d\u0430\u044f \u0438\u043d\u0444\u043e\u0440\u043c\u0430\u0446\u0438\u044f \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d\u0430"):
c(a.message)}d();var b=g(["Additionally"]);$.isBlank(b)||$.post($.CustomerCard.GetCustomerUpdateUrl(),{data:b},a,"json")},SaveRemove:function(){function a(a){a.status?e("\u041f\u0440\u0438\u0447\u0438\u043d\u0430 \u0443\u0434\u0430\u043b\u0435\u043d\u0438\u044f \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d\u0430"):c(a.message)}d();var b=g(["ReasonForDeleted"]);$.isBlank(b)||$.post($.CustomerCard.GetCustomerUpdateUrl(),{data:b},a,"json")},SaveQuestionAnswer:function(a){d();
var b={Answer:$("#Answer_"+a).val()};$.post(BaseUrl+"customer/"+CustomerID+"/question/"+a+"/update",{data:b},function(a){a.status?(e("\u041e\u0442\u0432\u0435\u0442 \u043d\u0430 \u0432\u043e\u043f\u0440\u043e\u0441 \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d"),$.CustomerCard.ReloadQuestionList()):c(a.message)},"json")},SaveLanguageRecord:function(a){d();a={RecordID:a,LanguageID:$("#Language_"+a).val(),Level:$("#LevelLanguage_"+a).find("input:radio:checked").val()};
$.post(BaseUrl+"customer/"+CustomerID+"/language/save",a,function(a){a.status?(e("\u042f\u0437\u044b\u043a \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d"),$.CustomerCard.ReloadLanguageList()):c(a.message)},"json")},SaveEmailRecord:function(a){d();a={RecordID:a,Email:$("#Email_"+a).val(),Note:$("#Note_"+a).val()};$.post(BaseUrl+"customer/"+CustomerID+"/email/save",a,function(a){a.status?(e("E-Mail \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d"),
$.CustomerCard.ReloadEmailList()):c(a.message)},"json")},SaveChildrenRecord:function(a){d();a={RecordID:a,SexID:$("#ChildrenSex_"+a).find("input:radio:checked").val(),FIO:$("#ChildrenFIO_"+a).val(),Reside:IsLoveStory?$("#ChildrenReside_"+a).val():"",DOB:toServerDate($("#ChildrenDOB_"+a).val())};$.post(BaseUrl+"customer/"+CustomerID+"/children/save",a,function(a){a.status?(e("\u0420\u0435\u0431\u0435\u043d\u043e\u043a \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d"),
$.CustomerCard.ReloadChildrenList()):c(a.message)},"json")},SavePhoto:function(){function a(a){a.status?e("\u0414\u0430\u043d\u043d\u044b\u0435 \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d\u044b"):c(a.message)}d();var b=g(["DateLastPhotoSession"]);$.isBlank(b)||$.post($.CustomerCard.GetCustomerUpdateUrl(),{data:b},a,"json")},SavePhotoAndVideo:function(){function a(a){a.status?e("\u0414\u0430\u043d\u043d\u044b\u0435 \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d\u044b"):
c(a.message)}d();var b=g(["DateLastPhotoSession"]);$.isBlank(b)||$.post($.CustomerCard.GetCustomerUpdateUrl(),{data:b},a,"json")},SaveVideoLink:function(a,b){d();$.post(BaseUrl+"customer/"+CustomerID+"/video/add",{Type:b,Link:a},function(a){a.status?(e("\u0412\u0438\u0434\u0435\u043e \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0434\u043e\u0431\u0430\u0432\u043b\u0435\u043d\u043e"),b?$.CustomerCard.ReloadVideoAmateurList():$.CustomerCard.ReloadVideoVerificationList(),$(b?"#AmateurLink":"#VerificationLink").val("")):
c(a.message)},"json")},SaveVideoSiteLink:function(a,b,f){d();$.post(BaseUrl+"customer/"+CustomerID+"/video/site/link/add",{Site:a,Type:f,Link:b},function(b){if(b.status)switch(e("\u0412\u0438\u0434\u0435\u043e \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0434\u043e\u0431\u0430\u0432\u043b\u0435\u043d\u043e"),parseInt(f)){case 0:$.CustomerCard.ReloadVideoSiteVerificationList(a);$("#VideoSiteVerificationLink_"+a).val("");break;case 1:$.CustomerCard.ReloadVideoSiteAmateurList(a);$("#VideoSiteAmateurLink_"+
a).val("");break;case 2:$.CustomerCard.ReloadVideoSiteMailList(a),$("#VideoSiteMailLink_"+a).val("")}else c(b.message)},"json")},SaveSites:function(){d();var a=[];$("#WorkSite").find("input:checked").each(function(){a.push($(this).val())});var b=[];$(".note-site").each(function(){var a=$(this).val(),c=$(this).attr("record");b.push({id:c,note:a})});var f=[];$(".comment-site").each(function(){var a=$(this).val(),b=$(this).attr("record");f.push({id:b,comment:a})});$.post(BaseUrl+"customer/"+CustomerID+
"/site/save",{data:{sites:a,notes:b,comments:f}},function(a){a.status?(e("\u0421\u043f\u0438\u0441\u043e\u043a \u0441\u0430\u0439\u0442\u043e\u0432 \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d"),a.insert&&$.CustomerCard.ReloadSiteList()):c(a.message)},"json")},SaveVideoSites:function(){d();var a=[];$("#VideoSite").find("input:checked").each(function(){a.push($(this).val())});$.post(BaseUrl+"customer/"+CustomerID+"/video/site/save",{data:{sites:a}},function(a){a.status?
(e("\u0421\u043f\u0438\u0441\u043e\u043a \u0441\u0430\u0439\u0442\u043e\u0432 \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d"),a.insert&&$.CustomerCard.ReloadVideoSiteList()):c(a.message)},"json")},SaveReservationContact:function(){function a(a){a.status?e("\u0418\u043d\u0444\u043e\u0440\u043c\u0430\u0446\u0438\u044f \u043e \u043a\u043e\u043d\u0442\u0430\u043a\u0442\u0430\u0445 \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d\u0430"):
c(a.message)}d();var b=g(["ReservationContacts"]);$.isBlank(b)||$.post($.CustomerCard.GetCustomerUpdateUrl(),{data:b},a,"json")}};$.CustomerCard.Init();$('a[data-toggle="tab"]').on("shown.bs.tab",function(){d()});$("body").on("hidden.bs.modal",".remoteModal",function(){$(this).removeData("bs.modal")});window.location.hash&&$('a[href="'+window.location.hash+'"]').tab("show")});
