import app from 'flarum/admin/app';
import registerWidget from '../common/registerWidget';

app.initializers.add('deteh/online-guests', () => {
  registerWidget(app);

  app.extensionData
    .for('deteh-online-guests')
    .registerPermission(
      {
        permission: 'viewOnlineGuests',
        icon: 'fas fa-eye',
        label: app.translator.trans('deteh-online-guests.admin.permissions.view_online_guests_label'),
        allowGuest: true,
      },
      'view'
    )
    .registerSetting({
      setting: 'deteh-online-guests.online-duration',
      label: app.translator.trans('deteh-online-guests.admin.settings.online_duration_label'),
      type: 'number',
      min: 0,
    })
    .registerSetting({
      setting: 'deteh-online-guests.cache-duration',
      label: app.translator.trans('deteh-online-guests.admin.settings.cache_duration_label'),
      type: 'number',
      min: 0,
    });
});
