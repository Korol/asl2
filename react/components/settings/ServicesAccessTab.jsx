import React from 'react'
import { BootstrapTable, TableHeaderColumn } from 'react-bootstrap-table'
import { connect } from 'react-redux';
import { Button, Modal, FormGroup, FormControl, Glyphicon } from 'react-bootstrap'
import { updateServiceAccess } from './../../actions/setting-actions'
import LoadingPanel from '../LoadingPanel';

@connect((store) => {
    return {
        isFetchUsers: store.configState.setting.isFetchUsers,
        users: store.configState.setting.users
    };
})

export default class ServicesAccessTab extends React.Component {

    state = {
        id: 0
    };

    edit = (row, e) => {
        this.props.dispatch(updateServiceAccess(row.ID, e.target.checked));
    };

    checkboxFormatter = (cell, row) => {
        return (
            <input type="checkbox" onClick={this.edit.bind(this, row)} checked={row.Access ? true : false} />
        )
    };

    render() {
        return (
            <div>
            {this.renderTable(this.props.users)}
            </div>
        );
    }

    renderTable = (users) => {
        return (
            <BootstrapTable tableBodyClass="reactTableBody" data={users} striped={true} hover={true} options={{noDataText: "Нет данных для отображения"}}>
                <TableHeaderColumn dataField='ID' isKey={ true } className="colBtn" columnClassName="colBtn" hidden >#</TableHeaderColumn>
                <TableHeaderColumn dataField='Name'>Имя пользователя</TableHeaderColumn>
                <TableHeaderColumn dataField='Role'>Должность</TableHeaderColumn>
                <TableHeaderColumn dataField='Access' dataFormat={this.checkboxFormatter} dataAlign="center">Доступ</TableHeaderColumn>
            </BootstrapTable>
        )
    };

}