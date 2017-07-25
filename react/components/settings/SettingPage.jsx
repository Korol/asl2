import React from "react";
import { connect } from 'react-redux';
import { Tab, Row, Col, Nav, NavItem } from 'react-bootstrap'

import { fetchQuestionTemplate } from './../../actions/setting-actions'
import { fetchUsersTemplate } from './../../actions/setting-actions'
// import { fetchServicesTemplate } from './../../actions/setting-actions'

import LoadingPanel from '../LoadingPanel';
import ReportTab from './ReportTab';
import CommentsAccessTab from './CommentsAccessTab';
// import ServicesAccessTab from './ServicesAccessTab';
import CustomerProfileTabPane from './CustomerProfileTab';

/** Панель сообщений */
@connect((store) => {
    return {
        config: store.configState.config
    }
})
export default class SettingPage extends React.Component {

    render() {
        return this.props.config ? this.view() : ( <LoadingPanel /> );
    }

    view = () => {
        const { dispatch } = this.props;
        const { isCustomerProfile } = this.props.config.customers;

        let navs = [];
        let panes = [];

        // Вкладка "Отчеты"
        navs.push(<NavItem key="SettingReportTab" eventKey="SettingReportTab">Отчеты</NavItem>);
        panes.push(<Tab.Pane key="SettingReportTab" eventKey="SettingReportTab"><ReportTab /></Tab.Pane>);

        // Вкладка "Анкета для клиентов"
        if (isCustomerProfile) {
            navs.push(<NavItem key="SettingCustomerProfileTab" eventKey="SettingCustomerProfileTab">Анкета для клиентов</NavItem>);
            panes.push(<Tab.Pane key="SettingCustomerProfileTab" eventKey="SettingCustomerProfileTab" onEnter={() => dispatch(fetchQuestionTemplate())} ><CustomerProfileTabPane /></Tab.Pane>);
        }

        // Вкладка "Доступ к комментариям"
        navs.push(<NavItem key="SettingCommentsAccessTab" eventKey="SettingCommentsAccessTab">Доступ к комментариям</NavItem>);
        panes.push(<Tab.Pane key="SettingCommentsAccessTab" eventKey="SettingCommentsAccessTab" onEnter={() => dispatch(fetchUsersTemplate())} ><CommentsAccessTab /></Tab.Pane>);

        // Вкладка "Доступ к Услугам"
        // navs.push(<NavItem key="SettingServicesAccessTab" eventKey="SettingServicesAccessTab">Доступ к Услугам</NavItem>);
        // panes.push(<Tab.Pane key="SettingServicesAccessTab" eventKey="SettingServicesAccessTab" onEnter={() => dispatch(fetchServicesTemplate())} ><ServicesAccessTab /></Tab.Pane>);

        return (
            <Tab.Container id="setting-category" defaultActiveKey="SettingReportTab">
                <Row className="clearfix">
                    <Col sm={12}>
                        <Nav bsStyle="tabs">
                            {navs}
                        </Nav>
                    </Col>
                    <Col sm={12}>
                        <Tab.Content animation>
                            {panes}
                        </Tab.Content>
                    </Col>
                </Row>
            </Tab.Container>
        );
    }

}