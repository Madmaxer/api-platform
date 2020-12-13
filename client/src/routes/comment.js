import React from 'react';
import { Route } from 'react-router-dom';
import { List, Create, Update, Show } from '../components/comment/';

export default [
  <Route path="/comments/create" component={Create} exact key="create" />,
  <Route path="/comments/edit/:id" component={Update} exact key="update" />,
  <Route path="/comments/show/:id" component={Show} exact key="show" />,
  <Route path="/comments/" component={List} exact strict key="list" />,
  <Route path="/comments/:page" component={List} exact strict key="page" />
];
