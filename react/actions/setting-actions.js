import axios from "../client"
import qs from "qs";
import * as types from './action-types'

/** Сохранить E-Mail для отчетов */
export function saveReportEmail(email) {
    return function (dispatch) {
        axios.post("/setting/save", qs.stringify({ReportEmail: email}))
            .then((response) => {
                dispatch({type: types.SETTING_SAVE_REPORT_EMAIL, email: email});
            })
    }
}

/** E-Mail для отчетов */
export function changeReportEmail(email) {
    return {
        type: types.SETTING_CHANGE_REPORT_EMAIL,
        email: email
    }
}

/**
 * Загрузка списка вопросов для пользовательской анкеты
 */
export function fetchQuestionTemplate() {
    return function (dispatch) {
        dispatch({type: types.FETCH_SETTING_CUSTOMER_PROFILE_START});

        axios.get("/question/template")
            .then((response) => {
                dispatch({
                    type: types.FETCH_SETTING_CUSTOMER_PROFILE_SUCCESS,
                    questions: response.data.questions
                })
            })
            .catch((error) => {
                dispatch({type: types.FETCH_SETTING_CUSTOMER_PROFILE_FAILED, payload: error})
            })
    };
}

/**
 * Добавление нового вопроса
 *
 * @param {string} question - текст вопроса
 */
export function addQuestion(question) {
    return function (dispatch) {
        dispatch({type: types.APPEND_SETTING_CUSTOMER_PROFILE_START});

        axios.post("/question/template/add", qs.stringify({question: question}))
            .then((response) => {
                dispatch({type: types.APPEND_SETTING_CUSTOMER_PROFILE_SUCCESS, id: response.data.id, question: question})
            })
            .catch((error) => {
                dispatch({type: types.APPEND_SETTING_CUSTOMER_PROFILE_FAILED, payload: error})
            })
    }
}

/**
 * Сохранение вопроса
 *
 * @param {number}  id          - ID вопроса
 * @param {string}  question    - текст вопроса
 */
export function editQuestion(id, question) {
    return function (dispatch) {
        dispatch({type: types.EDIT_SETTING_CUSTOMER_PROFILE_START});

        axios.post("/question/template/edit", qs.stringify({id: id, question: question}))
            .then((response) => {
                dispatch({type: types.EDIT_SETTING_CUSTOMER_PROFILE_SUCCESS, id: id, question: question})
            })
            .catch((error) => {
                dispatch({type: types.EDIT_SETTING_CUSTOMER_PROFILE_FAILED, payload: error})
            })
    }
}

/**
 * Удаление вопроса
 *
 * @param {number} id - ID вопроса
 */
export function removeQuestion(id, checked) {
    return function (dispatch) {
        dispatch({type: types.REMOVE_SETTING_CUSTOMER_PROFILE_START});

        axios.post("/question/template/remove", qs.stringify({id: id}))
            .then((response) => {
                dispatch({type: types.REMOVE_SETTING_CUSTOMER_PROFILE_SUCCESS, id: id, checked: checked})
            })
            .catch((error) => {
                dispatch({type: types.REMOVE_SETTING_CUSTOMER_PROFILE_FAILED, payload: error})
            })
    }
}

/**
 * Загрузка списка сотрудников для контроля доступа к комментариям
 */
export function fetchUsersTemplate() {
    return function (dispatch) {
        dispatch({type: types.FETCH_SETTING_USERS_START});

        axios.get("/users/template")
            .then((response) => {
                //console.log(response);
                dispatch({
                    type: types.FETCH_SETTING_USERS_SUCCESS,
                    users: response.data.users
                })
            })
            .catch((error) => {
                dispatch({type: types.FETCH_SETTING_USERS_FAILED, payload: error})
            })
    };
}

/**
 * Обновление доступа
 *
 * @param {number} id - ID пользователя
 */
export function updateAccess(id, checked) {
    return function (dispatch) {
        dispatch({type: types.UPDATE_SETTING_EMPLOYEE_ACCESS_START});

        var checkval = (checked) ? 1 : 0;
        axios.post("/users/template/update", qs.stringify({id: id, checked: checkval}))
            .then((response) => {
                //console.log(response);
                dispatch({type: types.UPDATE_SETTING_EMPLOYEE_ACCESS_SUCCESS, id: id, checked: checked})
            })
            .catch((error) => {
                dispatch({type: types.UPDATE_SETTING_EMPLOYEE_ACCESS_FAILED, payload: error})
            })
    }
}

/**
 * Загрузка списка сотрудников для контроля доступа к Услугам
 */
export function fetchServicesTemplate() {
    return function (dispatch) {
        dispatch({type: types.FETCH_SETTING_SERVICES_USERS_START});

        axios.get("/services/template")
            .then((response) => {
                //console.log(response);
                dispatch({
                    type: types.FETCH_SETTING_SERVICES_USERS_SUCCESS,
                    users: response.data.users
                })
            })
            .catch((error) => {
                dispatch({type: types.FETCH_SETTING_SERVICES_USERS_FAILED, payload: error})
            })
    };
}

/**
 * Обновление доступа к Услугам
 *
 * @param {number} id - ID пользователя
 */
export function updateServiceAccess(id, checked) {
    return function (dispatch) {
        dispatch({type: types.UPDATE_SETTING_EMPLOYEE_SERVICES_ACCESS_START});

        var checkval = (checked) ? 1 : 0;
        axios.post("/services/template/update", qs.stringify({id: id, checked: checkval}))
            .then((response) => {
                //console.log(response);
                dispatch({type: types.UPDATE_SETTING_EMPLOYEE_SERVICES_ACCESS_SUCCESS, id: id, checked: checked})
            })
            .catch((error) => {
                dispatch({type: types.UPDATE_SETTING_EMPLOYEE_SERVICES_ACCESS_FAILED, payload: error})
            })
    }
}