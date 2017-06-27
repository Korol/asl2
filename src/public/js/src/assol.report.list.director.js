$(document).ready(function(){
    "use strict";

    /** Список типов отчетов */
    const REPORT_TYPE_LIST = {Level: 0};
    /** Индивидуальные отчеты - список переводчиков */
    const REPORT_TYPE_INDIVIDUAL = {Level: 1, Name: 'Индивидуальные'};

    /** Список индивидулаьных отчетов переводчика */
    const REPORT_EMPLOYEE_LIST = {Level: 2, Name: getEmployeeName};
    /** Индивидуальные отчеты -> Ежедневный отчет */
    const REPORT_INDIVIDUAL_DAILY = {Level: 21, Name: 'Ежедневный отчет'};
    /** Индивидуальные отчеты -> Отчет по переписке */
    const REPORT_INDIVIDUAL_CORRESPONDENCE = {Level: 22, Name: 'Отчет по переписке'};
    /** Индивидуальные отчеты -> Отчет по рассылке */
    const REPORT_INDIVIDUAL_MAILING = {Level: 23, Name: 'Отчет по рассылке'};
    /** Индивидуальные отчеты -> Отчет по зарплате */
    const REPORT_INDIVIDUAL_SALARY = {Level: 24, Name: 'Отчет по зарплате'};

    /** Список общих отчетов */
    const REPORT_TYPE_GENERAL = {Level: 3, Name: 'Общие'};
    /** Общие отчеты -> Сводная зарплатная таблица */
    const REPORT_OVERALL_SALARY = {Level: 31, Name: 'Подтвержденная зарплата'};
    /** Общие отчеты -> Общая з/п таблица */
    const REPORT_GENERAL_SALARY = {Level: 32, Name: 'Выплата зарплаты'};
    /** Общие отчеты -> Общая таблица по клиентам */
    const REPORT_GENERAL_OF_CUSTOMERS = {Level: 33, Name: 'Общая таблица по клиентам'};
    /** Общие отчеты -> Сводная таблица распределения */
    const REPORT_OVERALL_ALLOCATION = {Level: 34, Name: 'Сводная таблица распределения'};
    /** Общие отчеты -> Клиенты <–> Сайты */
    const REPORT_OVERALL_CUSTOMERS_SITES = {Level: 35, Name: 'Клиенты &harr; Сайты'};
    /** Общие отчеты -> Ежедневный отчет по сотрудникам */
    const REPORT_DAILY_EMPLOYEES = {Level: 36, Name: 'Ежедневный отчет по сотрудникам'};

    var pathLevel = REPORT_TYPE_LIST.Level;
    var selectEmployeeName = '';
    var selectEmployeeId = 0;

    function getEmployeeName() {
        return selectEmployeeName;
    }

    function getIindividualBread() {
        var bread = [
            {Level: REPORT_TYPE_INDIVIDUAL.Level, Name: REPORT_TYPE_INDIVIDUAL.Name},
            {Level: REPORT_EMPLOYEE_LIST.Level, Name: REPORT_EMPLOYEE_LIST.Name}
        ];

        switch (pathLevel) {
            case REPORT_INDIVIDUAL_DAILY.Level:
                bread.push({Name: REPORT_INDIVIDUAL_DAILY.Name});
                break;
            case REPORT_INDIVIDUAL_MAILING.Level:
                bread.push({Name: REPORT_INDIVIDUAL_MAILING.Name});
                break;
            case REPORT_INDIVIDUAL_CORRESPONDENCE.Level:
                bread.push({Name: REPORT_INDIVIDUAL_CORRESPONDENCE.Name});
                break;
            case REPORT_INDIVIDUAL_SALARY.Level:
                bread.push({Name: REPORT_INDIVIDUAL_SALARY.Name});
                break;
        }

        bread[bread.length-1].IsLast = true;

        return bread;
    }

    function getGeneralBread() {
        var bread = [
            {Level: REPORT_TYPE_GENERAL.Level, Name: REPORT_TYPE_GENERAL.Name}
        ];

        switch (pathLevel) {
            case REPORT_OVERALL_SALARY.Level:
                bread.push({Name: REPORT_OVERALL_SALARY.Name, IsLast: true});
                break;
            case REPORT_OVERALL_ALLOCATION.Level:
                bread.push({Name: REPORT_OVERALL_ALLOCATION.Name, IsLast: true});
                break;
            case REPORT_GENERAL_OF_CUSTOMERS.Level:
                bread.push({Name: REPORT_GENERAL_OF_CUSTOMERS.Name, IsLast: true});
                break;
            case REPORT_GENERAL_SALARY.Level:
                bread.push({Name: REPORT_GENERAL_SALARY.Name, IsLast: true});
                break;
            case REPORT_OVERALL_CUSTOMERS_SITES.Level:
                bread.push({Name: REPORT_OVERALL_CUSTOMERS_SITES.Name, IsLast: true});
                break;
            case REPORT_DAILY_EMPLOYEES.Level:
                bread.push({Name: REPORT_DAILY_EMPLOYEES.Name, IsLast: true});
                break;
        }

        return bread;
    }

    var path = {
        main: {
            data: [
                {Level: REPORT_TYPE_INDIVIDUAL.Level, Name: REPORT_TYPE_INDIVIDUAL.Name.toUpperCase()},
                {Level: REPORT_TYPE_GENERAL.Level, Name: REPORT_TYPE_GENERAL.Name.toUpperCase()}
            ]
        },
        general: {
            bread: [{Name: REPORT_TYPE_GENERAL.Name, IsLast: true}],
            data: [
                {Level: REPORT_OVERALL_SALARY.Level, Name: REPORT_OVERALL_SALARY.Name, IsDoc: true},
                {Level: REPORT_GENERAL_SALARY.Level, Name: REPORT_GENERAL_SALARY.Name, IsDoc: true},
                {Level: REPORT_GENERAL_OF_CUSTOMERS.Level, Name: REPORT_GENERAL_OF_CUSTOMERS.Name,IsDoc: true},
                {Level: REPORT_OVERALL_ALLOCATION.Level, Name: REPORT_OVERALL_ALLOCATION.Name, IsDoc: true},
                {Level: REPORT_OVERALL_CUSTOMERS_SITES.Level, Name: REPORT_OVERALL_CUSTOMERS_SITES.Name, IsDoc: true},
                {Level: REPORT_DAILY_EMPLOYEES.Level, Name: REPORT_DAILY_EMPLOYEES.Name, IsDoc: true}
            ]
        },
        individual: {
            bread: getIindividualBread,
            data: [
                {Level: REPORT_INDIVIDUAL_DAILY.Level, Name: REPORT_INDIVIDUAL_DAILY.Name, IsDoc: true},
                {Level: REPORT_INDIVIDUAL_CORRESPONDENCE.Level, Name: REPORT_INDIVIDUAL_CORRESPONDENCE.Name, IsDoc: true},
                {Level: REPORT_INDIVIDUAL_MAILING.Level, Name: REPORT_INDIVIDUAL_MAILING.Name, IsDoc: true},
                {Level: REPORT_INDIVIDUAL_SALARY.Level, Name: REPORT_INDIVIDUAL_SALARY.Name, IsDoc: true}
            ]
        }
    };

    // Объект для публичного использования
    $.ReportListDirector = {
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

                if (pathLevel == REPORT_EMPLOYEE_LIST.Level) {
                    var name = record.find('.folder-name').html();
                    if (name)
                        selectEmployeeName = name;

                    var idEmployee = record.attr('id-employee');
                    if (idEmployee)
                        selectEmployeeId = idEmployee;
                }

                $.ReportListDirector.ReloadReportList();
            });
        },
        /** Инициализация динамичных данных */
        InitDynamicData: function() {
            this.ReloadReportList();
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

            function callback(data) {
                if (data.status) {
                    if (data.records){
                        var records = {
                            bread: [{Level: REPORT_TYPE_INDIVIDUAL.Level, Name: REPORT_TYPE_INDIVIDUAL.Name, IsLast: true}],
                            data: data.records
                        };

                        render(records);
                    }
                } else {
                    showErrorAlert(data.message)
                }
            }

            switch (pathLevel) {
                case REPORT_TYPE_LIST.Level:
                    render(path.main);
                    break;
                case REPORT_TYPE_INDIVIDUAL.Level:
                    $.post(BaseUrl + 'reports/data', {}, callback, 'json');
                    break;
                case REPORT_TYPE_GENERAL.Level:
                    render(path.general);
                    break;
                case REPORT_EMPLOYEE_LIST.Level:
                    render(path.individual);
                    break;
                case REPORT_INDIVIDUAL_DAILY.Level:
                case REPORT_INDIVIDUAL_MAILING.Level:
                case REPORT_INDIVIDUAL_CORRESPONDENCE.Level:
                case REPORT_INDIVIDUAL_SALARY.Level:
                    render({bread: getIindividualBread()});
                    break;
                case REPORT_OVERALL_SALARY.Level:
                case REPORT_OVERALL_ALLOCATION.Level:
                case REPORT_GENERAL_OF_CUSTOMERS.Level:
                case REPORT_GENERAL_SALARY.Level:
                case REPORT_OVERALL_CUSTOMERS_SITES.Level:
                case REPORT_DAILY_EMPLOYEES.Level:
                    render({bread: getGeneralBread()});
                    break;
                default:
                    alert('Ошибка загрузки данных');
            }

            $.ReportListDirector.ShowReport(pathLevel);
        },
        ShowReport: function (level) {
            $('#ReportIndividualDaily').toggle(level == REPORT_INDIVIDUAL_DAILY.Level);
            $('#ReportIndividualMailing').toggle(level == REPORT_INDIVIDUAL_MAILING.Level);
            $('#ReportIndividualCorrespondence').toggle(level == REPORT_INDIVIDUAL_CORRESPONDENCE.Level);
            $('#ReportIndividualSalary').toggle(level == REPORT_INDIVIDUAL_SALARY.Level);
            $('#ReportOverallSalary').toggle(level == REPORT_OVERALL_SALARY.Level);
            $('#ReportOverallAllocation').toggle(level == REPORT_OVERALL_ALLOCATION.Level);
            $('#ReportGeneralOfCustomers').toggle(level == REPORT_GENERAL_OF_CUSTOMERS.Level);
            $('#ReportGeneralSalary').toggle(level == REPORT_GENERAL_SALARY.Level);
            $('#ReportOverallCustomersSites').toggle(level == REPORT_OVERALL_CUSTOMERS_SITES.Level);
            $('#ReportDailyEmployees').toggle(level == REPORT_DAILY_EMPLOYEES.Level);

            switch (level) {
                case REPORT_INDIVIDUAL_DAILY.Level:
                    $.ReportTranslate.setEmployee(selectEmployeeId);
                    $.ReportTranslate.ReloadReportDailyMeta();
                    break;
                case REPORT_INDIVIDUAL_MAILING.Level:
                    $.ReportTranslate.setEmployee(selectEmployeeId);
                    $.ReportTranslate.ReloadReportMailing();
                    break;
                case REPORT_INDIVIDUAL_CORRESPONDENCE.Level:
                    $.ReportTranslate.setEmployee(selectEmployeeId);
                    $.ReportTranslate.ReloadReportCorrespondence();
                    break;
                case REPORT_INDIVIDUAL_SALARY.Level:
                    $.ReportTranslate.setEmployee(selectEmployeeId);
                    $.ReportTranslate.ReloadReportSalary();
                    break;
                case REPORT_OVERALL_SALARY.Level:
                    $('#overlaySalarySite').find('input:first').click();
                    break;
                case REPORT_OVERALL_ALLOCATION.Level:
                    $('#overallAllocationSite').find('input:first').click();
                    break;
                case REPORT_GENERAL_OF_CUSTOMERS.Level:
                    $.ReportDirector.ReloadReportGeneralOfCustomers();
                    break;
                case REPORT_GENERAL_SALARY.Level:
                    $.ReportDirector.ReloadReportGeneralSalary();
                    break;
                case REPORT_OVERALL_CUSTOMERS_SITES.Level:
                    $('#overallAllocationSite').find('input:first').click();
                    break;
                case REPORT_DAILY_EMPLOYEES.Level:
                    $('#overallAllocationSite').find('input:first').click();
                    break;
            }
        }
    };

    // Инициализация объекта
    $.ReportListDirector.Init();
});