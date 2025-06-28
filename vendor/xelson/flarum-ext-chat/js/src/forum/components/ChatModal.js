import Modal from 'flarum/components/Modal';
import ChatSearchUser from './ChatSearchUser';
import Stream from 'flarum/utils/Stream';
import classList from 'flarum/utils/classList';
import { fa5IconsList } from '../resources';
import highlight from 'flarum/helpers/highlight';
import FA5IconInput from './FA5IconInput';

export default class ChatModal extends Modal {
  oninit(vnode) {
    super.oninit(vnode);

    this.model = this.attrs.model;

    app.search.neonchat = { usersSelected: [] };
    this.usersSelected = app.search.neonchat.usersSelected;

    this.input = {
      title: Stream(''),
      color: Stream(''),
      icon: Stream(''),
      iconState: {
        matches: [],
        lastInput: null,
      },
    };
  }

  onremove(vnode) {
    super.onremove(vnode);
    app.search.neonchat = null;
  }

  getInput() {
    return this.input;
  }

  setSelectedUsers(users) {
    app.search.neonchat.usersSelected = users;
    this.usersSelected = app.search.neonchat.usersSelected;
  }

  getSelectedUsers() {
    return this.usersSelected;
  }

  className() {
    return 'ChatModal Modal--small';
  }

  isChatExists() {
    return this.getSelectedUsers().length === 1 && app.chat.isExistsPMChat(app.session.user, this.getSelectedUsers()[0]);
  }

  alertText() {
    if (this.isChatExists()) return app.translator.trans('xelson-chat.forum.chat.list.add_modal.alerts.exists');

    return null;
  }

  componentAlert() {
    return !this.alertText() ? null : <div className="Alert">{this.alertText()}</div>;
  }

  componentFormUsersSelect(label = 'xelson-chat.forum.chat.list.add_modal.form.users') {
    return [<label>{app.translator.trans(label)}</label>, this.componentUsersSelect()];
  }

  userMentionContent(user) {
    return '@' + user.displayName();
  }

  userMentionClassname(user) {
    return 'deletable';
  }

  userMentionOnClick(event, user) {
    return this.getSelectedUsers().splice(this.getSelectedUsers().indexOf(user), 1);
  }

  componentUsersMentions() {
    return (
      <div className="UsersTags">
        {this.getSelectedUsers().map((u) => (
          <div className={classList(['UserMention', this.userMentionClassname(u)])} onclick={this.userMentionOnClick.bind(this, u)}>
            {this.userMentionContent(u)}
          </div>
        ))}
      </div>
    );
  }

  componentUsersSelect() {
    return [
      this.componentAlert(),
      this.componentUsersMentions(),
      <div className="UsersSearch">
        <ChatSearchUser state={app.search} />
      </div>,
    ];
  }

  componentFormInput(options) {
    return [
      options.title ? <label>{options.title}</label> : null,
      <div>
        {options.desc ? <label>{options.desc}</label> : null}
        <input class="FormControl" type="text" bidi={options.stream} placeholder={options.placeholder} />
      </div>,
    ];
  }
}
