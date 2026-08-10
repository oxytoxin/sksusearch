# CLAUDE

## Solo Integration

This project uses [Solo](https://soloterm.com) to manage development processes.

### Available MCP Tools

When Solo is running with MCP enabled, use these tools:

| Tool | Description |
|------|-------------|
| `list_projects` | List Solo projects |
| `get_project` | Read metadata for the effective project scope |
| `create_project` | Register or import an existing local directory without opening app onboarding UI |
| `delete_project` | Delete the effective Solo project after explicit confirmation, optionally converting project prompt templates to global |
| `select_project` | Set the default project scope for later MCP tools |
| `rename_project` | Set or clear the display name for the effective project scope |
| `get_project_status` | Read project metadata and current processes for the effective project scope |
| `list_processes` | List process entries in the effective project scope |
| `get_process_status` | Read detailed status for one process |
| `rename_process` | Rename a process in the Solo UI |
| `select_process` | Select a process in the Solo UI so its terminal surface is attached and rendered |
| `start_process` | Start one existing Solo process entry by name or Solo process ID; this can start a stored command, terminal, or agent |
| `stop_process` | Gracefully stop one running process by name or ID |
| `restart_process` | Restart one existing Solo process entry by name or Solo process ID; this can restart a stored command, terminal, or agent |
| `close_process` | Remove one stored Solo terminal or Solo agent by name or Solo process ID; requires `confirm_self_close=true` if the target is the caller itself |
| `get_process_output` | Read recent rendered terminal output from one process |
| `search_output` | Search rendered terminal output from one process |
| `get_process_raw_output` | Read recent raw terminal output from one process |
| `search_raw_output` | Search raw terminal output from one process |
| `clear_output` | Clear Solo's saved output for one process |
| `send_input` | Send text or raw bytes to one running process |
| `get_process_ports` | List detected ports and URLs for one process |
| `get_project_stats` | Read CPU and memory usage for project processes |
| `services_list` | List detected project-local services, including readiness, URLs, and ports |
| `start_all_commands` | Start all trusted command processes in the effective project scope |
| `stop_all_commands` | Stop all running command processes in the effective project scope |
| `restart_all_commands` | Restart all trusted command processes in the effective project scope |
| `list_agent_tools` | List configured agent runtimes; use each returned `id` as `agent_tool_id` for `spawn_agent` or `spawn_process(kind="agent")` |
| `spawn_agent` | Create and start a new Solo agent; returns `process_id`, `name`, and optional bootstrap instructions; send the first prompt with `send_input` |
| `spawn_process` | Generic create/start tool for terminals or agents; use `kind="terminal"` for shells or `kind="agent"` with an `agent_tool_id` |
| `identify_session` | Identify this MCP session by auto-detection, by its own `SOLO_PROCESS_ID`, or as an external actor |
| `whoami` | Show the Solo-managed process tied to this MCP session |
| `submit_solo_feedback` | Open Solo's feedback form with a prefilled draft for human review and manual submission |
| `kv_set` / `kv_get` / `kv_list` / `kv_delete` | Shared project-scoped JSON state |
| `scratchpad_list` / `scratchpad_tags_list` / `scratchpad_read` / `scratchpad_find` / `scratchpad_tail` / `scratchpad_write` / `scratchpad_rename` / `scratchpad_add_tags` / `scratchpad_remove_tags` / `scratchpad_append` / `scratchpad_append_section` / `scratchpad_edit` / `scratchpad_clear` / `scratchpad_delete` / `scratchpad_archive` / `scratchpad_transfer` / `scratchpad_save_to_file` / `scratchpad_load_from_file` | Shared project-scoped scratchpads with revision checks, bounded literal search, tail reads, targeted section edits/appends, rename/tag operations, deletion, project moves, and file import/export |
| `todo_create` / `todo_list` / `todo_tags_list` / `todo_get` / `todo_update` / `todo_add_tag` / `todo_remove_tag` / `todo_transfer` / `todo_set_blockers` / `todo_add_blocker` / `todo_remove_blocker` / `todo_complete` / `todo_lock` / `todo_unlock` / `todo_delete` / `todo_comment_create` / `todo_comment_update` / `todo_comment_delete` / `todo_comment_list` | Shared project-scoped todos with tags, comments, blockers, locks, and cross-project moves |
| `lock_acquire` / `lock_status` / `lock_release` | Advisory lease-based coordination locks |
| `wait_for_bound_port` | Wait for a project-local process to expose a bound port in the effective project scope; useful for readiness and dev server URL discovery |
| `timer_set` / `timer_fire_when_idle_any` / `timer_fire_when_idle_all` / `timer_cancel` / `timer_pause` / `timer_resume` / `timer_list` | Durable timers with explicit Solo agent delivery targets; timer bodies are delivered by PTY injection as fresh user turns |
| `setup_agent_integration` | Write Solo MCP docs into `CLAUDE.md` or `AGENTS.md` |

**Note:** Solo uses `process` as the umbrella term for `command`, `terminal`, and `agent`. Bulk start/stop/restart tools only affect Solo command processes. `close_process` only applies to Solo terminals and Solo agents. If `close_process` targets this MCP session's own Solo process, pass `confirm_self_close=true` only when the user explicitly asked this process itself to close. Solo process IDs come from Solo tools such as `list_processes`, `get_process_status`, `spawn_agent`, and `spawn_process`; they are not non-Solo developer/runtime agent IDs. Solo command processes must be trusted in the Solo UI before MCP can start or restart them.

Project-scoped tools accept an optional `project_id` for one-off access. When `project_id` is omitted, Solo uses the selected project for the session if one is set; otherwise it falls back to the identified Solo process's project.

Solo-managed agents are normally auto-identified. `identify_session` accepts `solo_process_id` only to assert this MCP client's own `SOLO_PROCESS_ID` when auto-identification fails; it no-ops after identity exists and must not be used to target another process. External callers can pass `external` actor details for coordination identity. To wake a different agent, pass `delivery_process_id` to a timer tool. `SOLO_PROCESS_ID` is a Solo process ID, not a host OS PID.

### Recommended Solo MCP Workflow

1. Call `list_projects`, then either `select_project` to set a session default or pass `project_id` on individual project-scoped calls. Use `create_project(path, name?)` only to register or import an existing local directory. Use `delete_project` only with `confirm_delete=true`; add `confirm_stop_running=true` when running processes should be stopped and removed, and `prompt_template_policy=\"convert_to_global\"` when project prompt templates should be kept as global templates.
2. Call `whoami` to confirm `process_id`, `process_name`, `project_id`, `effective_project_id`, and `actor_id`.
3. If `whoami` cannot identify a Solo-managed child process, call `identify_session` with your own `solo_process_id` from `SOLO_PROCESS_ID` only to assert identity. If running outside Solo, call `identify_session` with `external` actor details. Never identify as a target process; use `delivery_process_id` for timer delivery.
4. To create a worker agent, call `list_agent_tools`, then `spawn_agent(agent_tool_id=N)`, then `send_input(process_id=<returned process_id>, input=<prompt>)`. Use `spawn_process(kind="terminal")` for a new shell.
5. Use `lock_acquire` / `lock_release` before editing shared files or logical work areas, and use KV, scratchpads, and todos for durable coordination state.
6. Use `services_list` to inspect detected local services, and prefer `wait_for_bound_port` and `timer_*` tools over tight polling loops when waiting on readiness or follow-up work.
7. Timers are the only Solo wake-up mechanism for Solo agent processes; pass `delivery_process_id` when scheduling a wake-up for a different agent process.
8. Use `list_prompts` / `get_prompt` for built-in playbooks such as `worker_bootstrap`, `wait_for_bound_port`, and `timer_followup`.
9. Use `submit_solo_feedback` when you uncover a Solo bug, UX issue, or feature request worth reporting upstream; Solo opens a prefilled feedback form for the human to review and submit.
