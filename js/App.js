import React from 'react';
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';
import AddCarPage from './AddCarPage';

const App = () => {
    return (
        <Router>
            <Switch>
                <Route path="/add-car" component={AddCarPage} />
                {/* Другие маршруты могут быть добавлены здесь */}
            </Switch>
        </Router>
    );
};

export default App;
