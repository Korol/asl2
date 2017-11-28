$(document).ready(function(){
    "use strict";

    /** Список отчетов */
    const REPORT_INDIVIDUAL_LIST = {Level: 0};
    /** Индивидуальные отчеты -> Ежедневный отчет */
    const REPORT_INDIVIDUAL_DAILY = {Level: 1, Name: 'Ежедневный отчет'};
    /** Индивидуальные отчеты -> Отчет по переписке */
    const REPORT_INDIVIDUAL_CORRESPONDENCE = {Level: 2, Name: 'Отчет по переписке'};
    /** Индивидуальные отчеты -> Отчет по рассылке */
    const REPORT_INDIVIDUAL_MAILING = {Level: 3, Name: 'Отчет по рассылке'};
    /** Индивидуальные отчеты -> Отчет по зарплате */
    const REPORT_INDIVIDUAL_SALARY = {Level: 4, Name: 'Отчет по зарплате'};
    /** Индивидуальные отчеты -> Подтвержденная зарплата */
    const REPORT_INDIVIDUAL_APPROVED_SALARY = {Level: 5, Name: 'Подтвержденная зарплата'};
    /** Индивидуальные отчеты -> Звонки */
    const REPORT_CALLS = {Level: 6, Name: 'Звонки'};

    var pathLevel = REPORT_INDIVIDUAL_LIST.Level;

    function getIindividualBread() {
        switch (pathLevel) {
            case REPORT_INDIVIDUAL_DAILY.Level:
                return {Name: REPORT_INDIVIDUAL_DAILY.Name, IsLast: true};
            case REPORT_INDIVIDUAL_MAILING.Level:
                return {Name: REPORT_INDIVIDUAL_MAILING.Name, IsLast: true};
            case REPORT_INDIVIDUAL_CORRESPONDENCE.Level:
                return {Name: REPORT_INDIVIDUAL_CORRESPONDENCE.Name, IsLast: true};
            case REPORT_INDIVIDUAL_SALARY.Level:
                return {Name: REPORT_INDIVIDUAL_SALARY.Name, IsLast: true};
            case REPORT_INDIVIDUAL_APPROVED_SALARY.Level:
                return {Name: REPORT_INDIVIDUAL_APPROVED_SALARY.Name, IsLast: true};
            case REPORT_CALLS.Level:
                return {Name: REPORT_CALLS.Name, IsLast: true};
            default:
                return [];
        }
    }

    var path = {
        individual: {
            data: [
                {Level: REPORT_INDIVIDUAL_DAILY.Level, Name: REPORT_INDIVIDUAL_DAILY.Name, IsDoc: true},
                {Level: REPORT_INDIVIDUAL_CORRESPONDENCE.Level, Name: REPORT_INDIVIDUAL_CORRESPONDENCE.Name, IsDoc: true},
                {Level: REPORT_INDIVIDUAL_MAILING.Level, Name: REPORT_INDIVIDUAL_MAILING.Name, IsDoc: true},
                {Level: REPORT_INDIVIDUAL_SALARY.Level, Name: REPORT_INDIVIDUAL_SALARY.Name, IsDoc: true},
                {Level: REPORT_INDIVIDUAL_APPROVED_SALARY.Level, Name: REPORT_INDIVIDUAL_APPROVED_SALARY.Name, IsDoc: true},
                {Level: REPORT_CALLS.Level, Name: REPORT_CALLS.Name, IsDoc: true}
            ]
        }
    };

    // Объект для публичного использования
    $.ReportListTranslate = {
        /** Инициализация объекта */
        Init: function() {
            this.InitActions();
            this.InitTemplate();
            this.InitDynamicData();
        },
        /** Инициализация событий */
        InitActions: function() {
            $(document).on('click', '.report-folder>a, .report-bread', function (e) {
                var record = $(e.target).closest('[level]');
                pathLevel = parseInt(record.attr('level'));

                $.ReportListTranslate.ReloadReportList();
            });
        },
        /** Инициализация динамичных данных */
        InitDynamicData: function() {
            $.ReportListTranslate.ReloadReportList();
        },
        /** Предварительная компиляция шаблонов */
        InitTemplate: function() {
            $("#reportsTemplate").template('reportsTemplate');
        },
        /** Загрузка списка папок и отчетов */
        ReloadReportList: function () {
            $("#reports").html('Загрузка данных...');

            function render(records) {
                $("#reports").empty();
                $.tmpl('reportsTemplate', records).appendTo("#reports");
            }

            switch (pathLevel) {
                case REPORT_INDIVIDUAL_LIST.Level:
                    render(path.individual);
                    break;
                case REPORT_INDIVIDUAL_DAILY.Level:
                case REPORT_INDIVIDUAL_MAILING.Level:
                case REPORT_INDIVIDUAL_CORRESPONDENCE.Level:
                case REPORT_INDIVIDUAL_SALARY.Level:
                case REPORT_INDIVIDUAL_APPROVED_SALARY.Level:
                case REPORT_CALLS.Level:
                    render({bread: getIindividualBread()});
                    break;
                default:
                    alert('Ошибка загрузки данных');
            }

            $.ReportListTranslate.ShowReport(pathLevel);
        },
        ShowReport: function (level) {
            $('#ReportIndividualDaily').toggle(level == REPORT_INDIVIDUAL_DAILY.Level);
            $('#ReportIndividualMailing').toggle(level == REPORT_INDIVIDUAL_MAILING.Level);
            $('#ReportIndividualCorrespondence').toggle(level == REPORT_INDIVIDUAL_CORRESPONDENCE.Level);
            $('#ReportIndividualSalary').toggle(level == REPORT_INDIVIDUAL_SALARY.Level);
            $('#ReportApprovedSalary').toggle(level == REPORT_INDIVIDUAL_APPROVED_SALARY.Level);
            $('#ReportCalls').toggle(level == REPORT_CALLS.Level);

            switch (level) {
                case REPORT_INDIVIDUAL_DAILY.Level:
                    $.ReportTranslate.ReloadReportDailyMeta();
                    break;
                case REPORT_INDIVIDUAL_MAILING.Level:
                    $.ReportTranslate.ReloadReportMailing();
                    break;
                case REPORT_INDIVIDUAL_CORRESPONDENCE.Level:
                    $.ReportTranslate.ReloadReportCorrespondence();
                    break;
                case REPORT_INDIVIDUAL_SALARY.Level:
                    $.ReportTranslate.ReloadReportSalary();
                    break;
                case REPORT_INDIVIDUAL_APPROVED_SALARY.Level:
                    $.ReportTranslate.ReloadReportApprovedSalary();
                    break;
                case REPORT_CALLS.Level:
                    loadCalls();
                    break;
            }
        }
    };

    // Инициализация объекта
    $.ReportListTranslate.Init();
});