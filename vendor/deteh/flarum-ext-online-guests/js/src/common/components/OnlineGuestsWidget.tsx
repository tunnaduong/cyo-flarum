import app from 'flarum/common/app';
import Widget, { WidgetAttrs } from 'flarum/extensions/afrux-forum-widgets-core/common/components/Widget';
import extractText from 'flarum/common/utils/extractText';

export default class OnlineGuestsWidget<T extends WidgetAttrs> extends Widget<T> {
  className(): string {
    return 'deteh-OnlineGuestsWidget';
  }

  icon(): string {
    return 'fas fa-users';
  }

  title(): string {
    return extractText(app.translator.trans('deteh-online-guests.forum.widget.title'));
  }

  content() {
    const guestCount = app.forum.attribute('onlineGuests');

    return (
      <div className="deteh-OnlineGuestsWidget-content">
        <span className="deteh-OnlineGuestsWidget-guestCount">{guestCount}</span>
        <span className="deteh-OnlineGuestsWidget-description">
          {app.translator.trans('deteh-online-guests.forum.widget.guests_online', { count: guestCount })}
        </span>
      </div>
    );
  }
}
