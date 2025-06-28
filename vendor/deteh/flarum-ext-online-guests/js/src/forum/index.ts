import app from 'flarum/forum/app';
import registerWidget from '../common/registerWidget';

app.initializers.add('deteh/online-guests', () => {
  registerWidget(app);
});
