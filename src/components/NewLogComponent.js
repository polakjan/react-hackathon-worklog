import React from 'react';
import ReactDOM from 'react-dom';

// config
import config from '../config/config';

// libs
import $ from 'jquery';

export default class NewLog extends React.Component
{
    constructor(props) {
        super(props);

        this.state = {
            time: 10,
            what: this.props.tasks.length ? this.props.tasks[0].id : '',
        }
    }

    formSubmitted() {

        $.ajax({
            method: 'post',
            url: config.api_url + 'logs/create',
            dataType: 'json',
            data: {
                duration: this.state.time,
                task_id: this.state.what
            }
        })
        .done((data) => {
           this.props.logWasAdded();
        });
    }

    inputChanged(ev, name) {
        let new_state = {};

        new_state[name] = ev.target.value;

        this.setState(new_state);
    }

    render() {

        let options = this.props.tasks.map((task) => {
            return <option key={ task.id } value={ task.id }>{ task.name }</option>
        });

        return (
            <div className="new-log">
            
                <h2>New log</h2>

                <form action="" onSubmit={ (event) => { event.preventDefault(); this.formSubmitted() } } >

                    <label htmlFor="time">For the last</label>

                    <select name="time" id="time" value={ this.state.time } onChange={ (ev) => this.inputChanged(ev, 'time') }>
                        <option value="10">10 minutes</option>
                        <option value="20">20 minutes</option>
                        <option value="30">30 minutes</option>
                        <option value="60">1 hour</option>
                        <option value="120">2 hours</option>
                    </select>

                    <label htmlFor="what">I have been doing</label>

                    <select name="what" id="what" value={ this.state.what } onChange={ (ev) => this.inputChanged(ev, 'what') } >
                        { options }
                    </select>

                    <input type="submit" value="save" />

                </form>

            </div>
        )
    }
}