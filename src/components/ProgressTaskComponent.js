import React from 'react';
import ReactDOM from 'react-dom';

export default class Progress extends React.Component
{

    sayTime() {

        let t = this.props.time || 0;
        
        if(t < 60) {
            return t + ' minutes';
        }
    
        let hs = Math.floor(t/60),
            mins = t % 60,
            text = '';

        text += hs + (hs==1 ? ' hour' : ' hours');

        if(mins) {
            text += ' ' + mins + (mins==1 ? ' minute' : ' minutes');
        }

        return text;
    }

    render() {

        return (
            <li>
                <div className="name">{ this.props.name }</div>
                <div className="time">{ this.sayTime() }</div>
            </li>
        )
    }
}