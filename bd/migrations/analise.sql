create table public.analise (
    id text not null,
    qid text not null,
    created_at timestamp with time zone not null default now(),
    classe text null,
    response text not null,
    assign text not null,
    payload json null,
    release text not null,
    resume text null,
    constraint perfil_pkey primary key (id)
) TABLESPACE pg_default;