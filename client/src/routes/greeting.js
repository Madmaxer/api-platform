import React from 'react';
import { Route } from 'react-router-dom';
import { List, Create, Update, Show } from '../components/greeting/';

export default [
  <Route path="/greetings/create" component={Create} exact key="create" />,
  <Route path="/greetings/edit/:id" component={Update} exact key="update" />,
  <Route path="/greetings/show/:id" component={Show} exact key="show" />,
  <Route path="/greetings/" component={List} exact strict key="list" />,
  <Route path="/greetings/:page" component={List} exact strict key="page" />
];
