import React from 'react';
import ReactDOM from 'react-dom';

// config
import config from '../config/config';

// libs
import $ from 'jquery';

export default class NewTask extends React.Component
{
    constructor(props) {
        super(props);

        this.state = {
            task_name: ''
        }
    }

    formSubmitted() {
        console.log({
            name: this.state.task_name
        });
        $.ajax({
            method: 'post',
            url: config.api_url + 'tasks/create',
            dataType: 'json',
            data: {
                name: this.state.task_name
            }
        })
        .done((data) => {
            this.setState({
                task_name: ''
            });
            this.props.taskWasAdded();
        });
    }

    render() {
        return (
            <div className="new-task">
            
                <h2>New task</h2>

                <form action="" onSubmit={ (event) => { event.preventDefault(); this.formSubmitted() } }>
                
                    <label htmlFor="name">Name</label>

                    <input type="text" name="name" id="name" value={ this.state.task_name } onChange={ (event) => { this.setState({ task_name: event.target.value })} } />

                    <input type="submit" value="save" />

                </form>

            </div>
        )
    }
}