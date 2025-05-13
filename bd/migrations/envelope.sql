create table public.perfil (
    id character varying not null,
    qid character varying not null default 'BETA'::character varying,
    created_at timestamp with time zone not null default now(),
    classe character varying null,
    response character varying not null,
    assign character varying not null,
    payload json null,
    release character varying not null,
    resume character varying null,
    constraint perfil_pkey primary key (id)
) TABLESPACE pg_default;