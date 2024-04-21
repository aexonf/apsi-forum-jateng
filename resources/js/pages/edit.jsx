import { toast } from "sonner";
import axios from "axios";
import Layout from "@/components/elements/layout";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { cn } from "@/lib/utils";
import { zodResolver } from "@hookform/resolvers/zod";
import { Link } from "@inertiajs/inertia-react";
import draftToHtml from "draftjs-to-html";
import htmlToDraft from "html-to-draftjs";
import { ContentState, EditorState, convertToRaw } from "draft-js";
import {
    BoldIcon,
    ChevronLeft,
    ImageIcon,
    ItalicIcon,
    LinkIcon,
    List,
    ListOrdered,
    RedoIcon,
    RemoveFormattingIcon,
    StrikethroughIcon,
    UnderlineIcon,
    UndoIcon,
} from "lucide-react";
import React, { useEffect, useState } from "react";
import { Editor } from "react-draft-wysiwyg";
import "react-draft-wysiwyg/dist/react-draft-wysiwyg.css";
import { useForm } from "react-hook-form";
import z from "zod";
import { Inertia } from "@inertiajs/inertia";

const formSchema = z.object({
    title: z.string().min(1, "Title harus di isi."),
    content: z.string().min(1, "Content harus di isi."),
});

export default function EditForum({ id }) {
    const token = localStorage.getItem("token");
    const [data, setData] = useState(null);
    const [editorState, setEditorState] = useState(null);
    const [dataProfile, setDataProfile] = useState(null);
    const [loading, setLoading] = useState(false);

    async function getData() {
        try {
            const response = await axios.get("/api/v1/user", {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            setDataProfile(response.data.data);
        } catch (error) {
            toast.error("Gagal mengambil data.");
        }
    }

    useEffect(() => {
        getData();
    }, []);

    const onEditorStateChange = function (editorState) {
        setEditorState(editorState);
        setValue(
            "content",
            draftToHtml(convertToRaw(editorState.getCurrentContent()))
        );
    };

    const uploadCallback = (file) => {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => {
                const url = reader.result;
                resolve({ data: { link: url } });
            };
            reader.onerror = (error) => {
                reject(error);
            };
        });
    };
    const {
        register,
        handleSubmit,
        formState: { errors },
        watch,
        reset,
        setValue,
    } = useForm({
        resolver: zodResolver(formSchema),
        defaultValues: {
            title: data?.title,
            content: "",
        },
    });
    async function fetchData() {
        setLoading(true);
        try {
            const res = await axios.get(`/api/v1/forum/detail/${id}`, {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            const data = res.data.data;
            setData(data);
            setValue("title", data.title);
            const blocksFromHTML = htmlToDraft(data.content);
            const state = ContentState.createFromBlockArray(
                blocksFromHTML.contentBlocks,
                blocksFromHTML.entityMap
            );
            const editorState = EditorState.createWithContent(state);
            setEditorState(editorState);
            console.log("123", blocksFromHTML);
            setLoading(false);
        } catch (err) {
            setLoading(false);
        }
    }

    useEffect(() => {
        fetchData();
    }, []);

    const submit = async (data) => {
        const { title, content } = data;
        const fData = new FormData();
        fData.append("title", title);
        fData.append("content", content);

        await axios
            .put(
                `/api/v1/forum/${id}`,
                {
                    title: title,
                    content: content,
                },
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                }
            )
            .then((res) => {
                toast.success(res.data.message);
                reset();
                setValue("content", null);
                Inertia.get(`/post/${res.data.data.id}`);
            })
            .catch((err) => {
                toast.error(err.response.data.message);
            });
    };

    return (
        <Layout>
            <div className="flex items-center justify-between gap-3 my-4">
                <Button variant="ghost" size="icon" asChild>
                    <Link href="/">
                        <ChevronLeft />
                    </Link>
                </Button>
                <h3 className="scroll-m-20 text-2xl font-semibold tracking-tight">
                    Ubah Forum
                </h3>
                <span></span>
            </div>
            <div className="w-full flex flex-col gap-y-4">
                <Card className="w-full bg-white p-4 rounded-lg shadow-md">
                    <CardContent>
                        <div className="flex items-center space-x-4 my-4">
                            <Avatar>
                                {dataProfile?.img_url && (
                                    <AvatarImage
                                        alt="User profile picture"
                                        src={`/storage/${dataProfile?.img_url}`}
                                    />
                                )}
                                <AvatarFallback></AvatarFallback>
                            </Avatar>
                            <div>
                                <div className="font-semibold text-lg">
                                    {dataProfile?.name}
                                </div>
                                <div className="text-gray-500">
                                    {dataProfile?.label}
                                </div>
                            </div>
                        </div>
                        <form
                            className="flex flex-col space-y-4"
                            onSubmit={handleSubmit(submit)}
                        >
                            <div className="flex flex-col space-y-2">
                                <Label htmlFor="title">Judul</Label>
                                <Input
                                    id="title"
                                    disabled={loading}
                                    placeholder="Title"
                                    {...register("title")}
                                    onChange={(e) => {
                                        setValue("title", e.target.value);
                                    }}
                                    className={cn(
                                        errors.title
                                            ? "border-destructive focus-visible:outline-destructive focus-visible:ring-destructive"
                                            : ""
                                    )}
                                />
                                {errors.title && (
                                    <p className="text-red-500">
                                        {errors.title?.message}
                                    </p>
                                )}
                            </div>
                            <div className="flex flex-col space-y-2">
                                <Label htmlFor="content">Konten</Label>
                                <div className="bg-muted">
                                    <Editor
                                        disabled={loading}
                                        editorState={editorState}
                                        toolbarClassName="toolbarClassName"
                                        wrapperClassName="wrapperClassName"
                                        editorClassName="editorClassName"
                                        onEditorStateChange={
                                            onEditorStateChange
                                        }
                                        value={watch("content")}
                                        toolbar={{
                                            options: [
                                                "history",
                                                "blockType",
                                                "textAlign",
                                                "colorPicker",
                                                "inline",
                                                "remove",
                                                "list",
                                                "link",
                                                "image",
                                                "fontSize",
                                            ],
                                            history: {
                                                undo: { icon: UndoIcon.src },
                                                redo: { icon: RedoIcon.src },
                                            },
                                            textAlign: {
                                                inDropdown: true,
                                                options: [
                                                    "left",
                                                    "center",
                                                    "right",
                                                    "justify",
                                                ],
                                            },
                                            inline: {
                                                className: "inline-wrapper",
                                                options: [
                                                    "bold",
                                                    "italic",
                                                    "underline",
                                                    "strikethrough",
                                                    "monospace",
                                                ],
                                                bold: { icon: BoldIcon.src },
                                                italic: {
                                                    icon: ItalicIcon.src,
                                                },
                                                underline: {
                                                    icon: UnderlineIcon.src,
                                                },
                                                strikethrough: {
                                                    icon: StrikethroughIcon.src,
                                                },
                                            },
                                            remove: {
                                                icon: RemoveFormattingIcon.src,
                                            },
                                            list: {
                                                options: [
                                                    "unordered",
                                                    "ordered",
                                                ],
                                                unordered: {
                                                    icon: List.src,
                                                },
                                                ordered: {
                                                    icon: ListOrdered.src,
                                                },
                                            },
                                            link: {
                                                defaultTargetOption: "_self",
                                                options: ["link"],
                                                link: { icon: LinkIcon.src },
                                            },

                                            image: {
                                                uploadCallback: uploadCallback,
                                                alt: {
                                                    present: true,
                                                    mandatory: false,
                                                },
                                                previewImage: true,
                                                icon: ImageIcon.src,
                                            },
                                            fontSize: {
                                                options: [
                                                    8, 9, 10, 11, 12, 14, 16,
                                                    18, 24, 30, 36, 48, 60, 72,
                                                    96,
                                                ],
                                            },
                                        }}
                                    />
                                </div>
                                {errors.content && (
                                    <p className="text-red-500">
                                        {errors.content?.message}
                                    </p>
                                )}
                            </div>
                            <Button
                                disabled={loading}
                                className="bg-primary text-primary-foreground"
                                type="submit"
                            >
                                Ubah Forum
                            </Button>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </Layout>
    );
}
