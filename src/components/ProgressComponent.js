import React from 'react';
import ReactDOM from 'react-dom';

// config
import config from '../config/config';

// libs
import $ from 'jquery';

import ProgressTask from './ProgressTaskComponent';

export default class Progress extends React.Component
{
    constructor(props) {
        super(props);

        this.state = {
            tasks: []
        }
    }

    componentDidMount() {

        this.reloadTasks();

    }

    reloadTasks() {

        $.ajax({
            method: 'get',
            url: config.api_url + 'tasks/totals',
            dataType: 'json'
        })
        .done((data) => {
            this.setState((prevState, props) => {
                return {
                    tasks: data
                }
            });
        })
        .catch(function (error) {
            console.log(error);
        });

    }

    render() {

        let tasks = this.state.tasks.map((task) => {
            console.log(task);
            return <ProgressTask key={ task.id } name={ task.name } time={ task.total } />
        });

        return (
            <div className="progress">
            
                <h2>Total progress</h2>

                <ul>
                    
                    { tasks }

                </ul>

            </div>
        )
    }
}