import React from 'react';
import ReactDOM from 'react-dom';

export default class Log extends React.Component
{

    reloadLogs() {
        // TODO
    }

    render() {

        // TODO
        
        return (
            <div className="log">
            
                <h2>Last logs:</h2>

                <div className="controls">
                    <label htmlFor="mine">
                        Only mine: <input type="checkbox" name="mine" value="1" />
                    </label>
                </div>

                <ul>
                    <li>
                        <div className="name">Task name</div>
                        <div className="time">1 hour</div>
                    </li>
                </ul>

            </div>
        )
    }
}