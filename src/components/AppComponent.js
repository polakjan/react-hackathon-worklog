import React from 'react';
import ReactDOM from 'react-dom';

// config
import config from '../config/config';

// libs
import $ from 'jquery';

// components
import NewLogForm from './NewLogComponent';
import NewTaskForm from './NewTaskComponent';
import Log from './LogComponent';
import Progress from './ProgressComponent';

export default class App extends React.Component
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
            url: config.api_url + 'tasks',
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
    

    logWasAdded() {

        this.log.reloadLogs();
        this.progress.reloadTasks();

    }

    render() {

        return (
            <div id="page">

                <h1>Worklog</h1>

                <div>
            
                    <div className="left">

                        <NewLogForm tasks={ this.state.tasks } logWasAdded={ () => this.logWasAdded() } />
            
                        <NewTaskForm taskWasAdded={ () => this.reloadTasks() } />
            
                    </div>
            
                    <div className="right">
            
                        <Log ref={ e => { this.log = e } }/>

                        <Progress ref={ e => { this.progress = e } } />
            
                    </div>
            
                </div>

            </div>
        )
    }
}